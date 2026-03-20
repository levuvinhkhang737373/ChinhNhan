<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNeed extends Model
{
    protected $table = 'customerneeds';

    protected $fillable = [
        'title',
        'description',
        'friendly_url',
        'cat_id',
        'display',
        'picture',
    ];

    protected function catId(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn($value) => $value
                ? array_map(fn($v) => (int) trim($v), explode(',', $value))
                : [],
            set: fn($value) => is_array($value)
                ? implode(', ', $value)
                : $value
        );
    }

}
