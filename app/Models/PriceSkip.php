<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceSkip extends Model
{
    use HasFactory;

    protected $table = 'product_price_skip';

    protected $fillable = [
        'MaHH',
        'TenHH',
        'admin_id',
        'type',
        'price',
    ];

    protected $casts = [
        'type'  => 'string',
        'price' => 'integer',
    ];

    public $timestamps = true;
}
