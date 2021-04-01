<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

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
}
