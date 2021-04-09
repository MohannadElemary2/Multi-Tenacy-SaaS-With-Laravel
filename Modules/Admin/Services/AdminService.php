<?php

namespace Modules\Admin\Services;

use App\Http\Resources\FailureResource;
use Modules\Admin\Repositories\AdminRepository;
use Illuminate\Support\Facades\Hash;
use App\Services\BaseService;
use App\Traits\HasAuthentications;
use Illuminate\Http\Response;
use Modules\Admin\Transformers\AdminResource;

class AdminService extends BaseService
{
    use HasAuthentications;

    public function __construct(AdminRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Login admin and retrieve token from oauth server
     *
     * @param  array  $data
     * @param  ServerRequestInterface $serverRequest
     * @return FailureResource|SuccessResource
     * @author Mohannad Elemary
     */
    public function login($data, $serverRequest)
    {
        // Validating admin credintials
        $admin = $this->repository->where([
            'email' => $data['email']
        ])->first(['*'], false);

        if (!$admin || !Hash::check($data['password'], $admin->getAuthPassword())) {
            return abort(new FailureResource([], __('admin/messages.invalid_credentials'), Response::HTTP_BAD_REQUEST));
        }

        // Issue Access Token From Oauth Server
        $result = $this->tokenRequest($serverRequest, $data);

        // Validate If token generated successfully
        if ($result['statusCode'] != Response::HTTP_OK) {
            return abort(new FailureResource([], $result['response']['error_description'], $result['statusCode'], Response::HTTP_BAD_REQUEST));
        }

        return array_merge(
            ["admin" => new AdminResource($admin)],
            $result['response']
        );
    }
}
