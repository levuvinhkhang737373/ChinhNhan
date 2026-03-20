<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    use HasFactory;
    protected $table      = 'import_log';
    protected $primaryKey = 'id';
    public $timestamps    = true;

    protected $fillable = [
        'admin_id',
        'cat_id',
        'file_name',
        'status',
        'imported_count',
        'not_found_count',
        'not_found_product',
    ];

}
