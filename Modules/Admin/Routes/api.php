<?php

/* System Routes */
Route::group(['as' => 'system.', 'prefix' => 'v1/system', 'namespace' => "V1\System"], function () {
    Route::post('login', 'AuthController@login')->name('login')->middleware("throttle:5,1");
    ;

    Route::get('settings', 'SettingsController@index')->name('settings.index');
    
    Route::group(
        ['middleware' => ['AddAuthHeader', 'auth:system-admins-api']],
        function () {
            Route::group(
                ['prefix' => 'settings'],
                function () {
                    Route::put('', 'SettingsController@bulkUpdate')->name('settings.bulk_update');
                }
            );
        }
    );
});
