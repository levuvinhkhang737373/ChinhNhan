<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'member_id',
        'title',
        'slug',
        'content',
        'thumbnail',
        'status',
        'display',
        'views',
    ];
    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}
