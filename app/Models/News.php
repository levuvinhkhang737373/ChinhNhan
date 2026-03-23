<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use MongoDB\Laravel\Eloquent\HybridRelations;
class News extends Model
{
    use HasFactory, Searchable,HybridRelations;
     protected $connection = 'mysql';
    protected $table = 'news';
    protected $primaryKey = 'news_id';
    protected $fillable = ['cat_id', 'cat_list', 'picture', 'focus', 'focus_order', 'views', 'display', 'menu_order', 'adminid','comment_count'];
    public function newsDesc()
    {
        return $this->hasOne(NewsDesc::class, 'news_id', 'news_id');
    }
    public function category()
    {
        return $this->hasMany(Category::class, 'cat_id', 'cat_id');
    }
    public function categoryDesc()
    {
        return $this->hasMany(NewsCategoryDesc::class, 'cat_id', 'cat_id');
    }
    public function viewers()
    {
        return $this->belongsToMany(Member::class, 'member_news_views');
    }
     public function comments()
    {
        return $this->hasMany(Comment::class,'news_id','news_id');
    }
    public function toSearchableArray()
    {
        $this->loadMissing('newsDesc');
        $desc = $this->newsDesc;
      return [
            'id'             => $this->news_id,
            'news_id'        => $this->news_id,
            'cat_id'         => $this->cat_id,
            'display'        => (int) $this->display,
            'title'          => $desc ? $desc->title : '',
            'short'          => $desc ? $desc->short : '',
            'description'    => $desc ? strip_tags($desc->description) : '',

      ];
    }
    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with('newsDesc');
    }
}
