<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterItem extends Model
{
    protected $table      = 'footer_items';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'footer_id',
        'name',
        'link',
    ];

    public function footer()
    {
        return $this->belongsTo(Footer::class, 'footer_id', 'id');
    }
}
