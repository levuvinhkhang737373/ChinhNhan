<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductNeed extends Model
{
    use HasFactory;

    protected $table      = 'product_needs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'description',
        'friendly_url',
        'cat_id',
        'display',
        'picture',
    ];

}
