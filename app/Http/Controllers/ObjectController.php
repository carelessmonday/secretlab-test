<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetObjectRequest;
use App\Models\ObjectModel;
use App\Models\ObjectValue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

class ObjectController extends Controller {

    public function store(Request $request)
    {
        try {
            $data = json_decode((string) $request->getContent(), TRUE, 512, JSON_THROW_ON_ERROR);

            foreach ($data as $key => $value) {
                ObjectModel::firstOrCreate([
                    'key' => trim($key)
                ]);
                ObjectValue::firstOrCreate([
                    'object_key' => trim($key),
                    'value'      => trim($value)
                ]);
            }

            return ['success' => TRUE];
        } catch (Exception $exception) {
            return ['success' => FALSE];
        }
    }

    public function show(string $key, GetObjectRequest $request)
    {
        $value = (new ObjectValue)->where('object_key', $key);

        if ($request->get('timestamp')) {
            $value = $value->where(
                'created_at', '>=',
                Carbon::createFromTimestamp((int) $request->get('timestamp'))
            )->first();
        } else {
            $value = $value->latest()->first();
        }

        abort_if($value === NULL, 404);

        return $value->value;
    }

    public function index()
    {
        return ObjectModel::with('latestValue')
            ->get()
            ->map(function ($item) {
                return [
                    'key'   => $item->key,
                    'value' => $item->latestValue->value
                ];
            });
    }
}
