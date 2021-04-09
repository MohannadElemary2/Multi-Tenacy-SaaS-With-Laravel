<?php

namespace App\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DropdownResource extends JsonResource
{
    /**
         * Transform the resource into an array.
         *
         * @param  Request
         * @return array
         */
        public function toArray($request)
        {
            $result = ['id' => $this->id];
            foreach ($this->dropdownAttributes as  $attribute)
                $result[$attribute] = $this->{$attribute};
            return  $result;
        }
}
