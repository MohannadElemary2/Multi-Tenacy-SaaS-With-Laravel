<?php

namespace App\Models;

use Hyn\Tenancy\Models\Website as HynWebsite;
use Modules\Client\Entities\System\Client;

class Website extends HynWebsite
{
    public function client()
    {
        return $this->hasOne(Client::class);
    }
}
