<?php

use Oxygen\Preferences\Loader\ConfigLoader;

Preferences::register('system.auth', function($schema) {
    $schema->setTitle('Authentication');
    $schema->setLoader(new ConfigLoader(App::make('config'), 'auth'));

    $schema->makeFields([
        '' => [
            'General' => [
                [
                    'name' => 'driver',
                    'type' => 'select',
                    'description' => Lang::get('oxygen/preferences::descriptions.auth.driver'),
                    'options' => [
                        'database' => 'Database',
                        'eloquent' => 'Eloquent',
                        'doctrine' => 'Doctrine'
                    ]
                ],
                [
                    'name' => 'model',
                    'description' => Lang::get('oxygen/preferences::descriptions.auth.model')
                ],
                [
                    'name' => 'table',
                    'description' => Lang::get('oxygen/preferences::descriptions.auth.table')
                ]
            ],
            'Password Reminders' =>[
                [
                    'name' => 'reminder.email',
                    'label' => 'Email View',
                    'description' => Lang::get('oxygen/preferences::descriptions.auth.reminder.email'),
                    'validationRules' => [
                        'view_exists'
                    ]
                ],
                [
                    'name' => 'reminder.table',
                    'description' => Lang::get('oxygen/preferences::descriptions.auth.reminder.table')
                ],
                [
                    'name' => 'reminder.expire',
                    'label' => 'Expires in',
                    'type' => 'number',
                    'description' => Lang::get('oxygen/preferences::descriptions.auth.reminder.expire')
                ]
            ],
        ]
    ]);
});