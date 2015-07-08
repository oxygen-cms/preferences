<?php

use Illuminate\Database\Migrations\Migration;
use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
use Oxygen\Preferences\Repository;

class CreateCorePreferenceItems extends Migration {

    /**
     * Run the migrations.
     */
    public function up() {
        $preferences = App::make(PreferenceRepositoryInterface::class);

        $item = $preferences->make();
        $item->setKey('appearance.admin');
        $data = new Repository([]);
        //$data->set('adminLayout', 'yourLayoutHere');
        $item->setPreferences($data);
        $preferences->persist($item, false);

        $item = $preferences->make();
        $item->setKey('appearance.themes');
        $data = new Repository([]);
        //$data->set('theme', 'aTheme');
        $item->setPreferences($data);
        $preferences->persist($item, false);

        $item = $preferences->make();
        $item->setKey('system.admin');
        $data = new Repository([]);
        $data->set('adminUriPrefix', 'oxygen');
        $item->setPreferences($data);
        $preferences->persist($item, false);

        $preferences->flush();
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        $preferences = App::make(PreferenceRepositoryInterface::class);

        $preferences->delete($preferences->findByKey('appearance.admin'), false);
        $preferences->delete($preferences->findByKey('appearance.themes'), false);
        $preferences->delete($preferences->findByKey('system.admin'), false);

        $preferences->flush();
    }
}
