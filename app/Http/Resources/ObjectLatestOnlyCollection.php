<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ObjectLatestOnlyCollection extends ResourceCollection {

    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return [
                    'key'       => $item->key,
                    'value'     => $item->latestValue->value,
                    'timestamp' => $item->latestValue->updated_at->unix()
                ];
            })];
    }
}
