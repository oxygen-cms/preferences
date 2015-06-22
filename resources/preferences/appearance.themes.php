<?php

use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
use Oxygen\Preferences\Loader\ThemeLoader;
use Oxygen\Preferences\Loader\DatabaseLoader;
use Oxygen\Preferences\PreferencesManager;
use Oxygen\Theme\ThemeManager;

Preferences::register('appearance.themes', function($schema) {
    $schema->setTitle('Themes');
    $schema->setLoader(new ThemeLoader(DatabaseLoader(app(PreferenceRepositoryInterface::class), 'appearance.themes'), app(PreferencesManager::class), app(ThemeManager::class), 'theme', 'theme'));
    $schema->setView('oxygen/preferences::themes.choose');

    $schema->makeField([
        'name' => 'theme',
        'label' => 'Theme',
        'type' => 'select',
        'options' => function() {
            $themes = Theme::all();
            $return = [];
            foreach($themes as $key => $theme) {
                $return[$key] = $theme->getName();
            }
            return $return;
        }
    ]);
});