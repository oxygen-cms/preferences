<?php

use Illuminate\Routing\Router;
use Oxygen\Preferences\Controller\PreferencesController;

$router = app('router');

$router->middleware('api_auth')->group(function(Router $router) {
    $router->get('/oxygen/api/preferences/{key}', [PreferencesController::class, 'getValue'])
        ->name("preferences.getValue")
        ->middleware("oxygen.permissions:preferences.getValue");
    
    $router->post('/oxygen/api/preferences/{key}/validate', [PreferencesController::class, 'postCheckIsValueValid'])
        ->name("preferences.postCheckIsValueValid")
        ->middleware("oxygen.permissions:preferences.putUpdate");

    $router->put('/oxygen/api/preferences/{key}', [PreferencesController::class, 'putUpdateValue'])
        ->name("preferences.putUpdateValue")
        ->middleware("oxygen.permissions:preferences.putUpdate");

});
