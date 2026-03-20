<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Guest extends Model
{
    protected $table='guests';
    protected $fillable = [
        'guest_id',
    ];
    // public function statisticsPages()
    // {
    //     return $this->hasMany(StatisticsPages::class,'guest_id', 'id');
    // }

}
