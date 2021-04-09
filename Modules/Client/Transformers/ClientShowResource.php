<?php

namespace Modules\Client\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientShowResource extends JsonResource
{
    private $ordersCount;
    private $hubsCount;
    private $productsCount;

    public function __construct($resource, $ordersCount, $hubsCount, $productsCount)
    {
        $this->ordersCount = $ordersCount;
        $this->hubsCount = $hubsCount;
        $this->productsCount = $productsCount;

        parent::__construct($resource);
    }

    public function toArray($request)
    {
        return  [
            'id'                => $this->id,
            'company_name'      => $this->company_name,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'domain'            => $this->domain,
            'is_active'         => $this->is_active,
            'no_of_orders'      => (int)$this->ordersCount,
            'no_of_hubs'        => (int)$this->hubsCount,
            'no_of_sku'         => (int)$this->productsCount,
            'created_at'        => $this->created_at,
        ];
    }
}
