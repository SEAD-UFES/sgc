<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

class ModelActivityHelper
{
    /**
     * @param string $event
     * @param Model $model
     *
     * @return Activity|null
     */
    public static function getModelEventActivity(string $event, Model $model): ?Activity
    {
        return Activity::where('subject_type', $model::class)
            ->where('subject_id', $model->getKey())
            ->where('event', $event)
            ->orderBy('id', 'desc')
            ->first();
    }
}
