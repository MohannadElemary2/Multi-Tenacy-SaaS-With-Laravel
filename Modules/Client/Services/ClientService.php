<?php

namespace Modules\Client\Services;

use App\Http\Resources\FailureResource;
use Modules\Client\Repositories\ClientRepository;
use App\Services\BaseService;
use Carbon\Carbon;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Repositories\WebsiteRepository;
use Illuminate\Http\Response;
use Modules\Client\Repositories\HubHistoryRepository;
use Modules\Client\Repositories\OrderHistoryRepository;
use Modules\Client\Repositories\ProductHistoryActivityRepository;
use Modules\Client\Repositories\ProductHistoryRepository;

class ClientService extends BaseService
{
    public function __construct(ClientRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Check if the current domain the user requesting on is valid and exists
     *
     * @return void
     * @author Mohannad Elemary
     */
    public function checkDomainExistence()
    {
        $domain = explode('.', request()->getHost())[0];

        $client = $this->repository->where([
            'domain' => $domain
        ])->first(['*'], false);

        if (!$client) {
            return abort(new FailureResource([], '', Response::HTTP_NOT_FOUND));
        }
    }

    public function show($id)
    {
        $client = $this->repository->find($id);

        $from = request()->from ?
            Carbon::createFromTimestamp(request()->from)->format('Y-m-d')
            : '2000-01-01';

        $to = request()->to ?
            Carbon::createFromTimestamp(request()->to)->format('Y-m-d')
            :  now()->format('Y-m-d');

        // Switch To Client Database
        $website = app(WebsiteRepository::class)->findById($client->website_id);
        app(Environment::class)->tenant($website);
        
        $ordersCount = $this->getClientOrdersCount($from, $to);
        $hubsCount = $this->getClientHubsCount($from, $to);
        $productsCount = $this->getClientProductsCount($from, $to);
        
        return new $this->repository->resource($client, $ordersCount, $hubsCount, $productsCount);
    }

    private function getClientOrdersCount($from, $to)
    {
        return app(OrderHistoryRepository::class)
                ->where([
                    ['day', '>=', $from]
                ])
                ->where([
                    ['day', '<=', $to]
                ])
                ->sum('orders_count');
    }

    private function getClientHubsCount($from, $to)
    {
        return app(HubHistoryRepository::class)
                ->where([
                    ['day', '>=', $from]
                ])
                ->where([
                    ['day', '<=', $to]
                ])
                ->max('hubs_count');
    }

    private function getClientProductsCount($from, $to)
    {
        $initalProducts = app(ProductHistoryRepository::class)
                ->where([
                    ['day', '>=', $from]
                ])
                ->where([
                    ['day', '<=', $to]
                ])
                ->orderBy('id', 'asc')
                ->first(['*'], false);

        $initalProducts = $initalProducts ? $initalProducts->active_products : '[]';
        $initalProducts = json_decode($initalProducts);
        
        $productActivities = app(ProductHistoryActivityRepository::class)
                ->where([
                    ['day', '>=', $from]
                ])
                ->where([
                    ['day', '<=', $to]
                ])
                ->distinct('product_id')
                ->pluck('product_id')
                ->toArray();

        return count(array_unique(array_merge($initalProducts, $productActivities), SORT_REGULAR));
    }
}
