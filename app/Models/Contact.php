<?php
namespace App\Models;

use App\Models\ContactStaff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $table      = 'contact';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'subject', 'saff_id', 'content', 'name', 'email', 'phone', 'address', 'status', 'menu_order', 'lang',
    ];

    public $timestamps = true;

    public function contactStaff()
    {
        return $this->belongsTo(ContactStaff::class, 'staff_id');
    }
}
