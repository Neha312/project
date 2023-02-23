<?php

namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

trait Uuids
{
    /**
     * Boot function from Laravel.
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
        static::creating(function ($model) {
            $model->created_by = auth()->user() ? auth()->user()->id : User::where('type', 'superadmin')->first()->id;
        });
        static::updating(function ($model) {
            $model->updated_by = auth()->user() ? auth()->user()->id : User::where('type', 'superadmin')->first()->id;
        });
        static::updating(function ($model) {
            $model->deleted_by = auth()->user() ? auth()->user()->id : User::where('type', 'superadmin')->first()->id;
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }
}
