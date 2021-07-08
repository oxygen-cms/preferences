<?php

use Oxygen\Preferences\Controller\PreferencesController;

$router = app('router');

$router->middleware(['web', 'oxygen.auth', '2fa.require'])->group(function() use ($router) {
    $router->get('/oxygen/api/preferences/{key}', PreferencesController::class . '@getValue')
        ->name("preferences.getValue")
        ->middleware("oxygen.permissions:preferences.getValue");
    
    $router->post('/oxygen/api/preferences/{key}/validate', PreferencesController::class . '@postCheckIsValueValid')
        ->name("preferences.postCheckIsValueValid")
        ->middleware("oxygen.permissions:preferences.putUpdate");

    $router->put('/oxygen/api/preferences/{key}', PreferencesController::class . '@putUpdateValue')
        ->name("preferences.putUpdateValue")
        ->middleware("oxygen.permissions:preferences.putUpdate");

});
