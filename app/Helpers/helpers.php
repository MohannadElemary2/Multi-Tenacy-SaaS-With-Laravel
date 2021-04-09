<?php

use App\Services\ModulesContactor;
use Hyn\Tenancy\Environment;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\App;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * Get The current tenant database connection name based on is the environment is unit testing or not
 *
 * @return string
 * @author Mohannad Elemary
 */
function currentTenantConnectionName()
{
    if (App::runningUnitTests()) {
        return 'sqlite_testing_tenant';
    }

    return 'tenant';
}

/**
 * Contact a Function From Another Service In Specific Modules
 *
 * @param string $service
 * @param string $method
 * @param mixed ...$methodArguments
 * @return mixed
 * @author Mohannad Elemary
 */
function contact($service, $method, ...$methodArguments)
{
    return app(ModulesContactor::class)->contact($service, $method, ...$methodArguments);
}

/**
 * Contact a static Function From Another Service In Specific Modules
 *
 * @param string $service
 * @param string $method
 * @param mixed ...$methodArguments
 * @return mixed
 * @author Mohamed Gnedy
 */
function contactStatic($service, $method, ...$methodArguments)
{
    return app(ModulesContactor::class)->contactStatic($service, $method, ...$methodArguments);
}

function getClientDomain($request = null)
{
    if (App::runningUnitTests()) {
        return 'testing';
    }

    if ($request) {
        $host = $request->getHost();
        $domain = is_string($host) ?  explode('.', $request->getHost())[0] : null;
    } elseif (app(Environment::class)->tenant()) {
        $domain = app(Environment::class)->tenant()->client->domain;
    }
    return $domain ?? null;
}

/**
 * Check if Bin Barcode Valid With Format: int-int-int
 *
 * @param string $barcode
 * @return boolean
 * @author Mohannad Elemary
 */
function isBinBarcodeValid($barcode)
{
    return preg_match('/^[0-9]{0,9}(\-[0-9]{0,9})(\-[0-9]{0,9})$/', $barcode);
}

/**
 * Get Cart ID From Bin Barcode
 *
 * @param string $barcode
 * @return int
 * @author Mohannad Elemary
 */
function getCartIDFromBinBarcode($barcode)
{
    return explode('-', $barcode)[0];
}

/**
 * Get Cart ID From Bin Barcode
 *
 * @param string $barcode
 * @return int
 * @author Mohannad Elemary
 */
function getBinIDFromBinBarcode($barcode)
{
    return explode('-', $barcode)[1];
}

/**
 * Get Serial From Bin Barcode
 *
 * @param string $barcode
 * @return int
 * @author Mohannad Elemary
 */
function getSerialFromBinBarcode($barcode)
{
    return explode('-', $barcode)[2];
}

/**
 * Check if the Exception is HttpClientResponseException
 *
 * @param Exception $e
 * @return bool
 * @author Mohamed Gnedy
 */

function isHttpClientResponseException(Exception $e): bool
{
    $retVal = false;
    if ($e instanceof HttpResponseException) {
        $response = $e->getResponse();
        $statusCode = $response->getStatusCode();
        $retVal  =  preg_match("/4[0-9]{2}/", $statusCode);
    }
    return $retVal;
}


/**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
function getDistanceBetweenTwoPoints(
    $latitudeFrom,
    $longitudeFrom,
    $latitudeTo,
    $longitudeTo,
    $earthRadius = 6371000
) {
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $lonDelta = $lonTo - $lonFrom;
    $a = pow(cos($latTo) * sin($lonDelta), 2) +
        pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
    $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

    $angle = atan2(sqrt($a), $b);
    return $angle * $earthRadius;
}

function paginate($items, $perPage = 20, $page = null, $options = [])
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

    $items = $items instanceof Collection ? $items : Collection::make($items);

    return new LengthAwarePaginator(
        $items->forPage($page, $perPage),
        $items->count(),
        $perPage,
        $page,
        $options
    );
}
