<?php
namespace App\Models;

use App\Models\Brand;
use App\Models\BrandDesc;
use App\Models\Category;
use App\Models\CategoryDesc;
use App\Models\ProductDesc;
use App\Models\ProductFlashSale;
use App\Models\ProductGroup;
use App\Models\ProductPicture;
use App\Models\ProductProperties;
use App\Models\ProductStatus;
use App\Models\ProductStatusDesc;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table      = 'products';
    protected $primaryKey = 'product_id';
    protected $fillable   = [
        'cat_id', 'cat_list', 'maso', 'macn', 'code_script',
        'picture', 'price', 'price_old', 'brand_id',
        'status',
        'TenHH',
        'ItmsGrpNam',
        'TenTrenWeb1SAP',
        'TenTrenWeb2SAP',
        'MaHH',
        'TonKho',
        'stock',
        'votes',
        'numvote',
        'menu_order',
        'views',
        'display',
        'Hienthi',
        'adminid',
        'type',
    ];

    //Thông số kỹ thuật (Sửa lại quan hệ bị lỗi)
    public function technologies()
    {
        return $this->hasManyThrough(
            ProductProperties::class,
            Price::class,
            'product_id',
            'price_id',
            'product_id',
            'id'
        )->with(['property', 'propertyValue']);
    }

    public function productProperties()
    {
        return $this->technologies();
    }

    public $timestamps = true;

    public function prices()
    {
        return $this->hasMany(Price::class, 'product_id', 'product_id');
    }

    public function price()
    {
        return $this->hasMany(Price::class, 'product_id', 'product_id');
    }

    public function categoryDes()
    {
        return $this->belongsTo(CategoryDesc::class, 'cat_id', 'cat_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id', 'cat_id');
    }

    public function productDesc()
    {
        return $this->hasOne(ProductDesc::class, 'product_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function brandDesc()
    {
        return $this->hasOne(BrandDesc::class, 'brand_id', 'brand_id');
    }

    public function productPicture()
    {
        return $this->hasMany(ProductPicture::class, 'product_id', 'product_id');
    }
    public function productGroups()
    {
        return $this->hasMany(ProductGroup::class, 'product_main', 'product_id');

    }
    public function productStatusDesc()
    {
        return $this->hasOne(ProductStatusDesc::class, 'status_id', 'status');
    }
    public function productStatus()
    {
        return $this->hasOne(ProductStatus::class, 'status_id', 'status');
    }
    public function productFlashSale()
    {
        return $this->belongsTo(ProductFlashSale::class, 'product_id', 'product_id');
    }

    public function priceList()
    {
        return $this->hasMany(Price::class, 'product_id', 'product_id');
    }

    public function getBrandNameAttribute()
    {
        if (empty($this->brand_id) || $this->brand_id == 0) {
            return null;
        }

        if ($this->relationLoaded('brand') && $this->brand) {
            return optional($this->brand->brandDesc)->title;
        }

        return optional($this->brandDesc)->title;
    }
    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'type', 'id');
    }
}
