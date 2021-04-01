<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ObjectResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'key'       => $this->object_key,
            'value'     => $this->value,
            'timestamp' => $this->updated_at->unix()
        ];
    }
}
