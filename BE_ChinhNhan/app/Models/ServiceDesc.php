<?php
namespace App\Models;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDesc extends Model
{
    use HasFactory;
    protected $table      = 'service_desc';
    protected $primaryKey = 'id';
    protected $fillable   = ['title', 'description', 'short', 'friendly_url', 'friendly_title', 'metakey', 'metadesc', 'lang'];

    public $timestamps = true;

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }
}
