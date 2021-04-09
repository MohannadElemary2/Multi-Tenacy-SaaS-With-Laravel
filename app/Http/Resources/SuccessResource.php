<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class SuccessResource extends JsonResource
{
    public static $wrap = false;
    private $message;
    private $code;
    private $cookies;

    /**
     * FailureResource constructor.
     *
     * @param $resource
     * @param string $message
     * @param int    $code
     */
    public function __construct($resource, $message = '', $code = Response::HTTP_OK, $cookies = [])
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->code = $code;
        $this->cookies = $cookies;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this->resource && is_array($this->resource) && isset($this->resource['data']) ?
            $this->resource : ['data' => $this->resource];

        if ($this->message && !empty($this->message)) {
            return array_merge(
                [
                'message' => $this->message,
                ],
                $data
            );
        }

        return $data;
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->code);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        if ($this->cookies) {
            $response->cookie(
                '_token', // key
                $this->cookies['access_token'] ?? null, // value
                20080, // minutes
                null, // path
                null, // domain
                true, // secure
                true, // httpOnly
                'None', // sameSite
            );
        }
    }
}
