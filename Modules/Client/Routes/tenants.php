<?php

/* Clients Routes */
Route::group(
    ['as' => 'client.', 'prefix' => 'api/v1/client', 'namespace' => "Modules\Client\Http\Controllers\V1\Client"],
    function () {
        Route::post('domain/check', 'ClientController@checkDomainExistence')->name('domain.checkDomainExistence');

        Route::group(
            ['middleware' => ['AddAuthHeader', 'auth:client-users-api']],
            function () {
                Route::group(
                    ['prefix' => 'settings'],
                    function () {
                        Route::get('', 'SettingsController@index')->name('settings.index');
                        Route::put('', 'SettingsController@bulkUpdate')->name('settings.bulk_update');
                    }
                );
            }
        );
    },
);
