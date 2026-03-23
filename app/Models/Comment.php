<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'comments';
    protected $fillable   = [
        'news_id',
        'member_id',
        'content',
        'parent_id',
        'root_id',
        'status'
    ];
    protected $attributes = [
        'status' => 1,
    ];
    public function news()
    {
        return $this->belongsTo(News::class, 'news_id', 'news_id');
    }
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
    public function replies()
    {
        return $this->hasMany(Comment::class, 'root_id', '_id')
            ->with('member')
            ->orderBy('created_at', 'asc');
    }

    public $timestamps = true;

}
