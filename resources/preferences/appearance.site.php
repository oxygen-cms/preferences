<?php

use Oxygen\Preferences\Loader\PreferenceRepositoryInterface;
use Oxygen\Preferences\Loader\DatabaseLoader;
use Oxygen\Preferences\ThemeSpecificPreferencesFallback;
use Oxygen\Core\Theme\ThemeManager;

Preferences::register('appearance.site', function ($schema) {
    $schema->setTitle('Themes');
    $schema->setLoader(
        new DatabaseLoader(app(PreferenceRepositoryInterface::class),
        'appearance.site',
        new ThemeSpecificPreferencesFallback(app(ThemeManager::class), 'appearance.site'))
    );

    $schema->makeField([
        'name' => 'errorViewPrefix',
        'label' => 'Views for HTTP Errors',
        'type' => 'text',
    ]);
});

