<?php

namespace App\Traits;

use App\Http\Resources\FailureResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

trait HasDBTransaction
{
    /**
     * Make a Database Transaction And Roleback All Performed Operations If Any Issue Occured
     *
     * @param Closure $closure
     * @return void
     * @author Mohannad Elemary
     */
    public function startDBTransaction($closure)
    {
        DB::connection(currentTenantConnectionName())->beginTransaction();

        try {
            $closure();
        } catch (\Exception $e) {
            DB::connection(currentTenantConnectionName())->rollBack();
            if (isHttpClientResponseException($e)) {
                abort($e->getResponse());
            }
            abort(new FailureResource([], __('messages.transaction_error'), Response::HTTP_BAD_REQUEST));
        }

        DB::connection(currentTenantConnectionName())->commit();
    }
}
