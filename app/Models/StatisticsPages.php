<?php

namespace App\Models;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatisticsPages extends Model
{
    use HasFactory;
    protected $table      = 'statistics_pages';
    protected $primaryKey = 'id_static_page';
    protected $fillable   = ['url', 'date', 'count', 'mem_id', 'module', 'action', 'ip', 'product_type','guest_id'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'mem_id', 'id')
            ->select('id', 'username', 'full_name')
            ->withDefault([
                'id'        => 0,
                'username'  => 'guest',
                'full_name' => 'Khách vãng lai',
            ]);
    }
    // public function guest()
    // {
      
    //     return $this->belongsTo(Guest::class, 'guest_id', 'id');
    // }
    public function newsDesc()
    {
        return $this->belongsTo(NewsDesc::class, 'url', 'friendly_url');
    }
}
