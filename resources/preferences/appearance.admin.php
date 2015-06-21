<?php

use Oxygen\Preferences\Loader\ConfigLoader;
    use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
    use Oxygen\Preferences\Loader\DatabaseLoader;

Preferences::addGroup('appearance', 'Appearance');

Preferences::register('appearance.admin', function($schema) {
    $schema->setTitle('Administration');
    $schema->setLoader(new DatabaseLoader(app(PreferenceRepositoryInterface::class), 'appearance.admin'));

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