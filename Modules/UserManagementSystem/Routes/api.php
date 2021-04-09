<?php

Route::group(
    ['as' => 'client.', 'prefix' => 'v1/client', 'namespace' => "V1\Client"], function () {

    // Verify account and set password
    Route::post('email/verify/{id}/{hash}', 'AuthController@verify')->name('domain.verify');
});