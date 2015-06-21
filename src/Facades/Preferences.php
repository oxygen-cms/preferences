<?php

namespace Oxygen\Preferences\Facades;

use Illuminate\Support\Facades\Facade;
use Oxygen\Preferences\PreferencesManager;

class Preferences extends Facade {

    protected static function getFacadeAccessor() {
        return PreferencesManager::class;
    }

}