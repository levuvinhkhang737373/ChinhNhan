<?php
namespace App\Models;

use App\Models\CategoryDesc;
use App\Models\ProductAdvertise;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table      = 'product_category';
    protected $primaryKey = 'cat_id';
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'cat_code',
        'parentid',
        'picture',
        'background',
        'color',
        'psid',
        'is_default',
        'is_buildpc',
        'show_home',
        'list_brand',
        'menu_order',
        'views',
        'display',
        'type',
    ];
    public function categoryDesc()
    {
        return $this->hasOne(CategoryDesc::class, 'cat_id');
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'cat_id', 'cat_id');
    }
    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parentid', 'cat_id');
    }
    public function productAdvertise()
    {
        return $this->hasMany(ProductAdvertise::class, 'cat_id', 'cat_id');
    }

    public function catProperties()
    {
        return $this->hasMany(PropertiesCategory::class, 'cat_id', 'cat_id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'type', 'id');
    }
}
