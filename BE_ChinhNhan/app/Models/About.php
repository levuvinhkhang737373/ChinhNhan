<?php
namespace App\Models;

use App\Models\AboutDesc;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;
    protected $table      = 'about';
    protected $primaryKey = 'about_id';
    protected $fillable   = ['picture', 'parentid', 'views', 'menu_order', 'display', 'adminid'];

    public function aboutDesc()
    {
        return $this->hasOne(AboutDesc::class, 'about_id');
    }
    public $timestamps = true;

}
