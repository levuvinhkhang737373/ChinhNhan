<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiCallbackLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'endpoint',
        'headers',
        'status',
        'error_message',
        'requestTrace',
        'responseDateTime',
        'responseCode',
        'responseMessage',
        'responseIndex',
        'referenceCode',
    ];
}
