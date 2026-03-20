<?php
namespace App\Models;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandDesc extends Model
{
    protected $table      = 'product_brand_desc';
    protected $primaryKey = 'id';
    use HasFactory;
    protected $fillable = [
        'brand_id',
        'title',
        'description',
        'frendly_url',
        'friendly_title',
        'metakey',
        'metadesc',
        'lang',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

}
