<?php

namespace App\Traits;

use ReflectionClass;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogActivity
{
    use LogsActivity;

    protected static $logFillable = true;

    public function getDescriptionForEvent($event)
    {
        return __choice('action_text', ['record' => static::getLogNameToUse(), 'action' => $event]);
    }

    // protected static $logName = 'default';
    // protected static $logOnlyDirty = true;
    // protected static $recordEvents = ['created', 'updated', 'deleting'];

    protected static function getLogNameToUse()
    {
        return (new ReflectionClass(static::class))->getShortName();
    }
}
