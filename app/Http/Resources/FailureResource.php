<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class FailureResource extends JsonResource
{
    public static $wrap = false;
    private $message;
    private $code;

    /**
     * FailureResource constructor.
     *
     * @param $resource
     * @param string $message
     * @param int    $code
     */
    public function __construct($resource, $message = '', $code = Response::HTTP_UNAUTHORIZED)
    {
        parent::__construct($resource);
        $this->message = $message ? $message : __('general.failed_operation');
        $this->code = $code;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'message' => $this->message,
            'errors' => $this->resource
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->code);
    }
}
