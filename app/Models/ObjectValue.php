<?php

namespace App\Models;

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
}
