<?php
namespace App\Models;

use App\Models\About;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutDesc extends Model
{
    use HasFactory;
    protected $table      = 'about_desc';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'about_id',
        'title',
        'description',
        'friendly_url',
        'friendly_title',
        'metakey',
        'metadesc',
        'lang',
    ];
    public function about()
    {
        return $this->belongsTo(About::class, 'about_id', 'about_id');
    }
    public $timestamps = true;
}
