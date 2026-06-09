<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasPublicId
{
    protected static function bootHasPublicId(): void
    {
        static::creating(function (Model $model) {
            if (empty($model->public_id)) {
                $model->public_id = static::generatePublicId();
            }
        });
    }

    public static function generatePublicId(): string
    {
        return static::$publicIdPrefix . '_' . \Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(26));
    }

    public static function findByPublicId(string $publicId): ?static
    {
        return static::where('public_id', $publicId)->first();
    }
}