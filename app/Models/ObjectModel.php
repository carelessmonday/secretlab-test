<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string $key
 */
class ObjectModel extends Model {

    use HasFactory;

    public $table = 'objects';
    protected $fillable = ['key'];
    protected $dates = ['created_at', 'updated_at'];

    public static function boot(): void
    {
        parent::boot();
        self::creating(static function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function values(): HasMany
    {
        return $this->hasMany(ObjectValue::class, 'object_key', 'key');
    }

    public function latestValue()
    {
        return $this->hasOne(ObjectValue::class, 'object_key', 'key')->latest();
    }
}
