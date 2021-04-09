<?php

namespace Modules\UserManagementSystem\Repositories;

use App\Repositories\BaseRepository;
use Modules\UserManagementSystem\Entities\Client\FirebaseToken;

class FirebaseTokenRepository extends BaseRepository
{
    public function model()
    {
        return FirebaseToken::class;
    }
}
