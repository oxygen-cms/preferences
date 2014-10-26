<?php

use Oxygen\Preferences\Loader\ConfigLoader;

Preferences::addGroup('oxygen', 'Oxygen');

Preferences::register('oxygen.core', function($schema) {
    $schema->setTitle('Administration');
    $schema->setLoader(new ConfigLoader(App::make('config'), 'oxygen/core::config'));

    $schema->makeFields([
        '' => [
            '' => [
                [
                    'name'  => 'baseURI',
                    'label' => 'Base URL'
                ],
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