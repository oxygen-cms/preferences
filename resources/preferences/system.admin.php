<?php

use Oxygen\Preferences\Loader\ConfigLoader;

Preferences::addGroup('system', 'System');

Preferences::register('system.admin', function($schema) {
    $schema->setTitle('Administration');
    $schema->setLoader(new ConfigLoader(App::make('config'), 'oxygen/core::config'));

    $schema->makeFields([
        '' => [
            '' => [
                [
                    'name'  => 'baseURI',
                    'label' => 'Base URL'
                ]
            ]
        ]
    ]);
});