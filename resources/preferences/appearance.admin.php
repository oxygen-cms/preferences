<?php

use Oxygen\Preferences\Loader\ConfigLoader;

Preferences::addGroup('appearance', 'Appearance');

Preferences::register('appearance.admin', function($schema) {
    $schema->setTitle('Administration');
    $schema->setLoader(new ConfigLoader(App::make('config'), 'oxygen/core::config'));

    $schema->makeFields([
        '' => [
            '' => [
                [
                    'name' => 'layout',
                    'label' => 'Admin Layout',
                    'validationRules' => [
                        'view_exists'
                    ]
                ]
            ]
        ]
    ]);
});