<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetObjectRequest;
use App\Http\Requests\StoreObjectRequest;
use App\Http\Resources\ObjectCollection;
use App\Http\Resources\ObjectLatestOnlyCollection;
use App\Http\Resources\ObjectResource;
use App\Models\ObjectModel;
use App\Models\ObjectValue;
use Exception;

class ObjectController extends Controller {

    public function store(StoreObjectRequest $request)
    {
        $objects = $request->validated()['objects'];

        try {
            foreach ($objects as $key => $value) {
                ObjectModel::firstOrCreate([
                    'key' => $key
                ]);
                ObjectValue::createOrUpdate($key, $value);
            }

            return ['success' => TRUE];
        } catch (Exception $exception) {
            return ['success' => FALSE, 'message' => $exception->getMessage()];
        }
    }

    public function show(string $key, GetObjectRequest $request)
    {
        $byTimestamp = ObjectValue::byTimestamp($key, $request->get('timestamp'));

        if (!$byTimestamp && $request->get('timestamp')) {
            return response()->json(['message' => 'Resource not found.'], 404);
        }

        if (!$value = ObjectValue::getLatest($key)) {
            return response()->json(['message' => 'Resource not found.'], 404);
        }

        return new ObjectResource($value);
    }

    public function latest()
    {
        return new ObjectLatestOnlyCollection(ObjectModel::with('latestValue')->paginate());
    }

    public function index()
    {
        return new ObjectCollection((new ObjectValue)->paginate());
    }
}
