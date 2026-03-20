<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberPostView extends Model
{
    protected $fillable = [
        'member_id',
        'news_id'
    ];
}
