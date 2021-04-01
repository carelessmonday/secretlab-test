<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetObjectRequest;
use App\Http\Requests\StoreObjectRequest;
use App\Http\Resources\ObjectCollection;
use App\Http\Resources\ObjectResource;
use App\Models\ObjectModel;
use App\Models\ObjectValue;
use Exception;

class ObjectController extends Controller {

    public function store(StoreObjectRequest $request)
    {
        $data = $request->validated()['objects'];

        try {
            foreach ($data as $key => $value) {
                ObjectModel::firstOrCreate([
                    'key' => $key
                ]);
                ObjectValue::firstOrCreate([
                    'object_key' => $key,
                    'value'      => $value
                ]);
            }

            return ['success' => TRUE];
        } catch (Exception $exception) {
            return ['success' => FALSE];
        }
    }

    public function show(string $key, GetObjectRequest $request)
    {
        if ($request->get('timestamp')) {
            $result = ObjectValue::byTimestamp($key, (int) $request->get('timestamp'));
            $value = $result ?: ObjectValue::getLatest($key);
        } else {
            $value = ObjectValue::getLatest($key);
        }

        if (!$value) {
            return response()->json(['message' => 'Resource not found.'], 404);
        }

        return new ObjectResource($value);
    }

    public function index()
    {
        return new ObjectCollection(ObjectModel::with('latestValue')->get());
    }
}
