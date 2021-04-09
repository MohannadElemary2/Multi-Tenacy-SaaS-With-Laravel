<?php

namespace Modules\Admin\Entities\System;

use App\Models\AuthBaseModel;
use App\Traits\UsesSystemConnection;
use Illuminate\Notifications\Notifiable;

class User extends AuthBaseModel
{
    use Notifiable, UsesSystemConnection;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

}
