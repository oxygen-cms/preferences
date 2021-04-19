<?php

use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
use Oxygen\Preferences\Loader\ThemeLoader;
use Oxygen\Preferences\Loader\DatabaseLoader;
use Oxygen\Preferences\PreferencesManager;
use Oxygen\Theme\ThemeManager;

Preferences::register('appearance.themes', function($schema) {
    $schema->setTitle('Themes');
    $schema->setLoader(new DatabaseLoader(app(PreferenceRepositoryInterface::class), 'appearance.themes'));

//    new ThemeLoader(
//
//        app(PreferencesManager::class),
//        app(ThemeManager::class),
//        'theme',
//        'theme'
//    ));

    $schema->makeField([
        'name' => 'theme',
        'label' => 'Theme',
        'type' => 'select',
        'options' => function() {
            $themes = app(ThemeManager::class)->all();
            $return = [];
            foreach($themes as $key => $theme) {
                $return[$key] = $theme->getName();
            }
            return $return;
        }
    ]);
});
