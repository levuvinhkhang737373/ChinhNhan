<?php
namespace App\Models;

use App\Models\Product;
use App\Models\ProductDesc;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table      = 'order_detail';
    protected $primaryKey = 'id';
    protected $fillable   = ['order_id', 'item_type', 'item_id', 'quantity', 'item_title',
        'item_price', 'subtotal', 'add_from', 'group_id'];
    protected $timestamp = true;

    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id', 'product_id');
    }
    public function productDesc()
    {
        return $this->belongsTo(ProductDesc::class, 'item_id', 'product_id');
    }
    public function order()
    {
        return $this->belongsTo(OrderSum::class, 'order_id', 'order_id');
    }
}
