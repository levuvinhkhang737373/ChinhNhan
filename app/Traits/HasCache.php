<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait HasCache
{
    
//     protected static function bootHasCache()
//     {
//         static::created(fn() => static::clearCache());
//         static::updated(fn() => static::clearCache());
//         static::deleted(fn() => static::clearCache());
//     }

//    protected static function clearCache()
// {
//     if (property_exists(static::class, 'cacheTag') && static::$cacheTag) {
//         Cache::tags([static::$cacheTag])->flush();
//     }
// }
}
