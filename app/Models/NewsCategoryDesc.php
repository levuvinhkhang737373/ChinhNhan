<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsCategoryDesc extends Model
{
    use HasFactory;
    protected $table      = 'news_category_desc';
    protected $primaryKey = 'id';
    protected $fillable   = ['cat_id', 'cat_name', 'description', 'friendly_url', 'friendly_title', 'metakey', 'metadesc', 'adminid'];
    public function newsCategory()
    {
        return $this->belongsTo(NewsCategory::class, 'cat_id', 'cat_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {

            if ($model->isDirty('cat_name')) {

                $baseSlug = Str::slug($model->cat_name);
                $slug = $baseSlug;
                $count = 1;

                while (self::where('friendly_url', $slug)
                    ->where('cat_id', '!=', $model->cat_id)
                    ->exists()
                ) {

                    $slug = $baseSlug . '-' . $count++;
                }

                $model->friendly_url = $slug;
            }
        });
    }
    public $timestamps = true;
}
