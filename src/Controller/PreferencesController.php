<?php

namespace Oxygen\Preferences\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Oxygen\Auth\Permissions\Permissions;
use Oxygen\Core\Controller\Controller;
use Oxygen\Preferences\PreferenceNotFoundException;
use Oxygen\Preferences\PreferencesManager;
use Oxygen\Core\Http\Notification;
use Oxygen\Preferences\Schema;

class PreferencesController extends Controller {

    private PreferencesManager $preferences;
    private Permissions $permissions;
    private Factory $validationFactory;

    /**
     * Constructs the AuthController.
     *
     * @param PreferencesManager $preferences
     */
    public function __construct(PreferencesManager $preferences, Permissions $permissions, Factory $validationFactory) {
        $this->preferences = $preferences;
        $this->permissions = $permissions;
        $this->validationFactory = $validationFactory;
    }

    /**
     * Form to update a preferences.
     *
     * @param string $key
     * @return JsonResponse
     * @throws PreferenceNotFoundException
     */
    public function getValue(string $key): JsonResponse {
        $errorResponse = $this->checkHasPermissions($key);
        if($errorResponse !== null) {
            return $errorResponse;
        }

        $schema = $this->preferences
            ->getSchema($this->getPreferencesGroup($key));

        $field = $schema->getField($this->getPreferencesField($key));
        $options = [];
        foreach($field->getOptions() as $value => $display) {
            $options[] = ['value' => $value, 'display' => $display ];
        }
        return response()->json([
            'value' => $this->getOrNull($key),
            'options' => $options,
            'status' => Notification::SUCCESS
        ]);
    }

    /**
     * Form to update a preferences.
     *
     * @param string $key
     * @param Request $request
     * @return JsonResponse
     * @throws PreferenceNotFoundException
     */
    public function postCheckIsValueValid(string $key, Request $request): JsonResponse {
        $errorResponse = $this->checkHasPermissions($key);
        if($errorResponse !== null) {
            return $errorResponse;
        }

        $schema = $this->preferences
            ->getSchema($this->getPreferencesGroup($key));

        $errorResponse = $this->checkIsValueValid($schema, $key, $request->input('value'));
        if($errorResponse !== null) {
            return $errorResponse;
        }

        return response()->json([
            'valid' => true,
            'status' => Notification::SUCCESS
        ]);
    }

    /**
     * Form to update a preferences.
     *
     * @param string $key
     * @param Request $request
     * @return JsonResponse
     * @throws PreferenceNotFoundException
     */
    public function putUpdateValue(string $key, Request $request): JsonResponse {
        $errorResponse = $this->checkHasPermissions($key);
        if($errorResponse !== null) {
            return $errorResponse;
        }

        $schema = $this->preferences
            ->getSchema($this->getPreferencesGroup($key));

        $value = $request->input('value');
        $errorResponse = $this->checkIsValueValid($schema, $key, $value);
        if($errorResponse !== null) {
            return $errorResponse;
        }

        $schema
            ->getRepository()
            ->set($this->getPreferencesField($key), $value);
        $schema->storeRepository();

        return response()->json([
            'content' => 'Preference updated',
            'value' => $this->getOrNull($key),
            'status' => Notification::SUCCESS
        ]);
    }

    /**
     * @param string $key
     * @return JsonResponse|null null if permissions were satisfied, a JsonResponse if permissions insufficient
     */
    private function checkHasPermissions(string $key) {
        if(!$this->hasPermissions($key)) {
            return response()->json([
                'content' => 'Insufficient permissions to retrieve these preferences',
                'permissions' => false,
                'status' => Notification::SUCCESS
            ]);
        }
        return null;
    }

    private function hasPermissions(string $prefsKey) {
        $requiredKey = 'preferences.' . str_replace('.', '_', $this->getPreferencesGroup($prefsKey));
        return $this->permissions->has($requiredKey);
    }

    /**
     * @param Schema $schema
     * @param string $key
     * @param $value
     * @return JsonResponse|null
     */
    private function checkIsValueValid(Schema $schema, string $key, $value): ?JsonResponse {
        $validationRules = $schema->getValidationRules();
        $rules = [];
        if(isset($validationRules[$this->getPreferencesField($key)])) {
            $rules = $validationRules[$this->getPreferencesField($key)];
        }

        $validator = $this->validationFactory->make([ 'value' => $value ], [ 'value' => $rules ]);
        if($validator->fails()) {
            return response()->json([
                'valid' => false,
                'reason' => $validator->messages()->first(),
                'status' => Notification::SUCCESS
            ]);
        }
        return null;
    }

    private function getOrNull(string $key) {
        $value = null;
        try {
            $value = $this->preferences->get($key);
        } catch(PreferenceNotFoundException $e) {

        }
        return $value;
    }

    private function getPreferencesGroup(string $key) {
        $keyParts = explode('::', $key);
        return $keyParts[0];
    }

    private function getPreferencesField(string $key) {
        $keyParts = explode('::', $key);
        return $keyParts[1];
    }

}
