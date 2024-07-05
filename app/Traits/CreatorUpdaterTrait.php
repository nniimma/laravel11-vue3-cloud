<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait CreatorUpdaterTrait
{
    protected static function bootCreatorUpdaterTrait()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
}
