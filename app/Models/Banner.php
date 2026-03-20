<?php
namespace App\Models;
use App\Traits\HasCache;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasCache;
    protected $table                  = 'banners';
    protected $primaryKey             = 'id';
    protected static string $cacheTag = 'banners';
    protected $fillable               = [
        'title',
        'image',
        'link',
        'position',
        'is_active',
        'sort_order',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
