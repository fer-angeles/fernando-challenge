<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettlemenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'key' => $this->key,
            'name' => strtoupper($this->name),
            'zone_type' => strtoupper($this->zone_type),
            'settlement_type' => (new SettlemenTypeResource($this->settlement_type))
        ];
    }
}
