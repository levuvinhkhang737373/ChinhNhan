<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
     
       protected $fillable = [
        'title',
        'display',
        'image',
    ];
     public function footerItems()
    {
        return $this->hasMany(FooterItem::class, 'footer_id', 'id');
    }
}
