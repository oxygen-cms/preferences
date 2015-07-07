<?php

use Illuminate\Database\Migrations\Migration;
use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
use Oxygen\Preferences\Repository;

class CreateCorePreferenceItems extends Migration {

    /**
     * Run the migrations.
     *
     * @param \Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface $preferences
     */
    public function up(PreferenceRepositoryInterface $preferences) {
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
     *
     * @param \Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface $preferences
     */
    public function down(PreferenceRepositoryInterface $preferences) {
        $preferences->delete($preferences->findByKey('appearance.admin'));
        $preferences->delete($preferences->findByKey('appearance.themes'));
        $preferences->delete($preferences->findByKey('system.admin'));
    }
}
