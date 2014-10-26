<?php

use Oxygen\Preferences\Loader\ConfigLoader;

Preferences::addGroup('system', 'System');

Preferences::register('system.app', function($schema) {
    $schema->setTitle('General');
    $schema->setLoader(new ConfigLoader(App::make('config'), 'app'));

    $schema->makeFields([
        '' => [
            'General' => [
                [
                    'name' => 'debug',
                    'type' => 'toggle',
                    'description' => Lang::get('oxygen/preferences::descriptions.app.debug')
                ],
                [
                    'name' => 'url',
                    'label' => 'Base URL',
                    'description' => Lang::get('oxygen/preferences::descriptions.app.url')
                ]
            ],
            'Timezone & Locale' =>[
                [
                    'name' => 'timezone',
                    'type' => 'select',
                    'description' => Lang::get('oxygen/preferences::descriptions.app.timezone'),
                    'options' => [
                        'UTC' => 'UTC'
                    ]
                ],
                [
                    'name' => 'locale',
                    'description' => Lang::get('oxygen/preferences::descriptions.app.locale')
                ],
                [
                    'name' => 'fallback_locale',
                    'description' => Lang::get('oxygen/preferences::descriptions.app.fallback_locale')
                ]
            ],
            'Security' => [
                [
                    'name' => 'key',
                    'description' => Lang::get('oxygen/preferences::descriptions.app.key'),
                    'validationRules' => [
                        'size:32'
                    ]
                ]
            ]
        ]
    ]);
});