<?php

/* Clients Routes */

Route::group(
    ['as' => 'client.', 'prefix' => 'api/v1/client', 'namespace' => "Modules\UserManagementSystem\Http\Controllers\V1\Client"],
    function () {

        // Verify account and set password
        Route::post('email/verify/{id}/{hash}', 'AuthController@verify')->name('domain.verify');

        // Login
        Route::post('login', 'AuthController@login')->name('auth.login')->middleware("throttle:5,1");

        // Forgot password
        Route::post('forgot-password', 'PasswordController@forgotPassword')->name('auth.forgot');

        // Reset Password
        Route::post('reset-password', 'PasswordController@resetPassword')->name('auth.reset');

        Route::group(
            ['middleware' => ['AddAuthHeader', 'auth:client-users-api']],
            function () {
                Route::group(
                    ['prefix' => 'profile'],
                    function () {
                        // User Profile
                        Route::get('', 'ProfileController@view')->name('profile.view');
                        Route::put('', 'ProfileController@edit')->name('profile.edit');

                        // Update time zone
                        Route::put('time-zone', 'ProfileController@updateTimeZone')->name('profile.update_time_zone');

                        // Update locale
                        Route::put('locale', 'ProfileController@updateLocale')->name('profile.update_locale');

                        Route::get('logout', 'ProfileController@logout')->name('profile.logout');

                        // Change Password
                        Route::put('change-password', 'PasswordController@changePassword')->name('auth.change_password');
                    }
                );

                Route::group(
                    ['prefix' => 'setup-wizard'],
                    function () {
                        // update IsSetupWizardFinished;
                        Route::put('is-finished', 'ProfileController@editIsSetupWizardFinished')->name('profile.update_is_setup_wizard_finished');
                    }
                );


                // User Permissions
                Route::get('permissions', 'PermissionController@index')->name('permissions.index');
                Route::apiResource('roles', 'RoleController');
                Route::apiResource('users', 'TenantUserController');

                Route::post('set-firebase-token', 'TenantUserController@setFirebaseToken')->name('users.setFirebaseToken');
            }
        );
    }
);
