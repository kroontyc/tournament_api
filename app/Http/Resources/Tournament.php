<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Tournament extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'name' => $this->name,
            'data' => $this->data,
            'reward' => $this->reward,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}