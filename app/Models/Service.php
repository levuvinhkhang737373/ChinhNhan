<?php
namespace App\Models;

use App\Models\ServiceDesc;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    use HasFactory;
    protected $table      = 'service';
    protected $primaryKey = 'service_id';
    protected $fillable   = ['picture', 'views', 'display', 'menu_order', 'adminid', 'date_post', 'date_update']
    ;
    public $timestamps = true;

    public function serviceDesc()
    {
        return $this->hasOne(ServiceDesc::class, 'service_id');
    }
}
