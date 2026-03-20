<?php
namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDesc extends Model
{
    protected $table      = 'product_descs';
    protected $primaryKey = 'id';
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'product_id',
        'title',
        'description',
        'short',
        'friendly_url',
        'friendly_title',
        'metakey',
        'metadesc',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

}
