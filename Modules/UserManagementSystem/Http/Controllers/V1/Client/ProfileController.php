<?php

namespace Modules\UserManagementSystem\Http\Controllers\V1\Client;

use Modules\UserManagementSystem\Services\TenantUserService;
use Modules\UserManagementSystem\Http\Requests\StoreTenantUserRequest;
use Modules\UserManagementSystem\Http\Requests\UpdateTenantUserRequest;
use App\Http\Controllers\BaseController;
use App\Http\Resources\SuccessResource;
use Illuminate\Http\Response;
use Modules\UserManagementSystem\Http\Requests\EditIsSetupWizardFinishedRequest;
use Modules\UserManagementSystem\Http\Requests\EditProfileRequest;
use Modules\UserManagementSystem\Http\Requests\UpdateLocaleRequest;
use Modules\UserManagementSystem\Http\Requests\UpdateTimeZoneRequest;
use Modules\UserManagementSystem\Transformers\TenantUser\TenantUserResource;

class ProfileController extends BaseController
{
    protected $storeRequestFile = StoreTenantUserRequest::class;
    protected $updateRequestFile = UpdateTenantUserRequest::class;
    protected $resource = TenantUserResource::class;
    protected $relations = ['roles.permissions'];

    public function __construct(TenantUserService $service)
    {
        parent::__construct($service);
    }

    /**
     * View Current User's profile
     *
     * @return FailureResource|SuccessResource
     * @author Mohannad Elemary
     */
    public function view()
    {
        return new SuccessResource(
            $this->service->viewProfile()
        );
    }

    /**
     * Edit Current User's profile
     *
     * @param EditProfileRequest $request
     * @return FailureResource|SuccessResource
     * @author Mohannad Elemary
     */
    public function edit(EditProfileRequest $request)
    {
        $this->service->editProfile($request->all(['name', 'email', 'phone']));
        return new SuccessResource([], __('usermanagementsystem/messages.profile.success'));
    }

    /**
     * Logout and revoke user token
     *
     * @return FailureResource|SuccessResource
     * @author Mohannad Elemary
     */
    public function logout()
    {
        $this->service->logout();
        return new SuccessResource([], '', Response::HTTP_OK, ['_token']);
    }


    /**
     * Update Current User's time zone
     *
     * @param UpdateTimeZoneRequest $request
     * @return FailureResource|SuccessResource
     * @author Mohannad Elemary
     */
    public function updateTimeZone(UpdateTimeZoneRequest $request)
    {
        $this->service->update($request->all(), auth()->id());
        return new SuccessResource(
            [],
            __('usermanagementsystem/messages.time_zone.update_success')
        );
    }


    /**
     * Update Current User's locale
     *
     * @param UpdateLocaleRequest $request
     * @return FailureResource|SuccessResource
     * @author Mohannad Elemary
     */
    public function updateLocale(UpdateLocaleRequest $request)
    {
        $this->service->update($request->all(), auth()->id());
        return new SuccessResource(
            [],
            __('usermanagementsystem/messages.locale.update_success')
        );
    }


    /**
     * Edit Current User's IsSetupWizardFinished field
     *
     * @param EditIsSetupWizardFinishedRequest $request
     * @return FailureResource|SuccessResource
     * @author Mohannad Elemary
     */
    public function editIsSetupWizardFinished(EditIsSetupWizardFinishedRequest $request)
    {
        $this->service->editProfile($request->all(['is_setup_wizard_finished']));
        return new SuccessResource([], __('usermanagementsystem/messages.setup_wizard.success'));
    }
}
