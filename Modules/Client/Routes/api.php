<?php

/* System Routes */
Route::group(
    [
        'as' => 'system.', 'prefix' => 'v1/system', 'namespace' => "V1\System", 'middleware' => ['AddAuthHeader', 'auth:system-admins-api']
    ],
    function () {

    // Clients Operations
        Route::apiResource('clients', 'ClientController');
        Route::post('clients/check-domain', 'ClientController@checkDomain');
    }
);
