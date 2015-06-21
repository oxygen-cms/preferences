<?php

use Oxygen\Preferences\Loader\ConfigLoader;
    use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
    use Oxygen\Preferences\Loader\DatabaseLoader;
    use Oxygen\Preferences\Loader\ThemeLoader;

Preferences::register('appearance.themes', function($schema) {
    $schema->setTitle('Themes');
    $schema->setLoader(new DatabaseLoader(app(PreferenceRepositoryInterface::class), 'appearance.themes'));
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