<?php

use Illuminate\Database\Migrations\Migration;
use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
use Oxygen\Preferences\PreferencesManager;
use Oxygen\Preferences\Repository;

class CreateAdminAppearance extends Migration {

    /**
     * Run the migrations.
     */
    public function up() {
        $preferences = app(PreferencesManager::class);

        $schema = $preferences->getSchema('appearance.admin');
        $schema->getRepository()->set('logoDark', '');
        $schema->storeRepository();
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        $preferences = app(PreferencesManager::class);

        $schema = $preferences->getSchema('appearance.admin');
        $schema->getRepository()->set('logoDark', null);
        $schema->storeRepository();
    }
}
