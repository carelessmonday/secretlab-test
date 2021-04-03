<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Exception;

/**
 * @property string $id
 * @property string $object_key
 * @property string $value
 */
class ObjectValue extends Model {

    use HasFactory;

    public $table = 'object_values';
    protected $fillable = [
        'object_key',
        'value'
    ];
    protected $dates = ['created_at', 'updated_at'];

    public static function boot(): void
    {
        parent::boot();
        self::creating(static function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function getValueAttribute($value)
    {
        try {
            return json_decode($value, TRUE, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $exception) {
            return $value;
        }
    }

    public function objectModel(): BelongsTo
    {
        return $this->belongsTo(ObjectModel::class, 'object_key', 'key');
    }

    public static function byTimestamp(string $key, int $timestamp)
    {
        return (new static())
            ->where('object_key', $key)
            ->where('updated_at', '<=', Carbon::createFromTimestamp($timestamp))
            ->latest()
            ->first();
    }

    public static function getLatest(string $key)
    {
        return (new static())
            ->where('object_key', $key)
            ->latest()
            ->first();
    }

    public static function createOrUpdate(string $key, $inputValue): self
    {
        $value = is_array($inputValue)
            ? json_encode($inputValue, JSON_THROW_ON_ERROR)
            : $inputValue;

        $object = (new static)
            ->where('object_key', $key)
            ->where('value', $value)
            ->first();

        if ($object === NULL) {
            return self::create([
                'object_key' => $key,
                'value'      => $value
            ]);
        }

        $object->touch();

        return $object;
    }
}
