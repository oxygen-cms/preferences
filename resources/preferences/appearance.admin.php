<?php

use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
use Oxygen\Preferences\Loader\DatabaseLoader;

Preferences::register('appearance.admin', function($schema) {
    $schema->setTitle('Admin appearance');
    $schema->setLoader(new DatabaseLoader(app(PreferenceRepositoryInterface::class), 'appearance.admin'));

    $schema->makeField([
        'name' => 'logoDark',
        'label' => 'Vendor Logo',
        'type' => 'text'
    ]);
});
