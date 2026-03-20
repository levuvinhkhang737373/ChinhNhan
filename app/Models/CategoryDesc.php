<?php
namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryDesc extends Model
{
    protected $table      = 'product_category_desc';
    protected $primaryKey = 'id';
    use HasFactory;

    protected $fillable = [
        'cat_name',
    ];

    public $timestamps = true;

    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }
    public function productAdvertise()
    {
        return $this->hasMany(ProductAdvertise::class, 'cat_id', 'cat_id');
    }

}
