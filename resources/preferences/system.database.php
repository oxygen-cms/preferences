<?php

use Oxygen\Preferences\Loader\ConfigLoader;

Preferences::register('system.database', function($schema) {
    $schema->setTitle('Database');
    $schema->setLoader(new ConfigLoader(App::make('config'), 'database'));

    $schema->makeFields([
        'Connections' => [
            '' => [
                [
                    'name' => 'default',
                    'label' => 'Default Connection',
                    'type' => 'select',
                    'description' => Lang::get('oxygen/preferences::descriptions.database.default'),
                    'options' => [
                        'sqlite' => 'SQLite',
                        'mysql' => 'MySQL',
                        'pgsql' => 'PostgreSQL',
                        'sqlsrv' => 'SQL Server'
                    ]
                ]
            ]
        ],
        '' => [
            'Migrations' =>[
                [
                    'name' => 'migrations',
                    'label' => 'Migrations Table',
                    'description' => Lang::get('oxygen/preferences::descriptions.database.migrations')
                ]
            ],
            'Redis' => [
                [
                    'name' => 'redis.cluster',
                    'label' => 'Cluster',
                    'type' => 'toggle'
                ]
            ]
        ]
    ]);
});