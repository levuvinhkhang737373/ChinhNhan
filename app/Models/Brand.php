<?php
namespace App\Models;

use App\Models\BrandDesc;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table      = 'product_brand';
    protected $primaryKey = 'brand_id';
    use HasFactory;
    protected $fillable = [
        'cat_id',
        'picture',
        'focus',
        'menu_order',
        'views',
        'display',
        'date_post',
        'date_update',
        'adminid',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;

    public function brandDesc()
    {
        return $this->hasOne(BrandDesc::class, 'brand_id', 'brand_id');
    }
    public function category()
    {
        return $this->hasMany(Category::class, 'cat_id', 'cat_id');
    }

}
