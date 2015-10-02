<?php

use Oxygen\Preferences\Loader\Database\PreferenceRepositoryInterface;
use Oxygen\Preferences\Loader\DatabaseLoader;

Preferences::register('cacheSettings', function($schema) {
    $schema->setTitle('CacheSettings');
    $schema->setLoader(new DatabaseLoader(app(PreferenceRepositoryInterface::class), 'cacheSettings'));
});