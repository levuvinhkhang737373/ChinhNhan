<?php
namespace App\Models;

use App\Models\Advertise;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adpos extends Model
{
    protected $table      = 'ad_pos';
    protected $primaryKey = 'id_pos';
    use HasFactory;

    protected $fillable = [
        'name',
        'cat_id',
        'title',
        'width',
        'height',
        'n_show',
        'description',
        'display',
        'menu_order',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;

    public function advertise()
    {
        return $this->hasMany(Advertise::class, 'pos', 'id_pos');
    }
}
