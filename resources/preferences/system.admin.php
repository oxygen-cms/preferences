<?php

use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
use Oxygen\Preferences\Loader\DatabaseLoader;

Preferences::addGroup('system', 'System');

Preferences::register('system.admin', function($schema) {
    $schema->setTitle('Administration');
    $schema->setLoader(new DatabaseLoader(app(PreferenceRepositoryInterface::class), 'system.admin'));

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