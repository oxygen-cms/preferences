<?php

use Oxygen\Preferences\Loader\ConfigLoader;
use Oxygen\Preferences\Loader\ThemeLoader;

Preferences::register('appearance.themes', function($schema) {
    $schema->setTitle('Themes');
    $schema->setLoader(new ThemeLoader(App::make('config'), App::make('Oxygen\Core\Theme\ThemeManager')));
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