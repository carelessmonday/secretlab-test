<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ObjectCollection extends ResourceCollection {

    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return [
                    'key'       => $item->object_key,
                    'value'     => $item->value,
                    'timestamp' => $item->updated_at->unix()
                ];
            })];
    }
}
