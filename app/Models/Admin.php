<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\DB;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admin';
    protected $primaryKey = 'id';

    protected $fillable = [
        'username', 'password', 'email', 'display_name', 'avatar', 'skin',
        'depart_id', 'is_default', 'lastlogin', 'code_reset', 'menu_order',
        'status', 'phone', 'created_at', 'updated_at', 'type',
    ];

    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'admin_role', 'admin_id', 'role_id');
    // }
    public function roles()
{
    return $this->belongsToMany(
        Role::class,
        'admin_role',
        'admin_id',
        'role_id'
    );
}

    public function hasPermission($permissionSlug)
{
    return DB::table('admin_role')
        ->join('role_permission', 'admin_role.role_id', '=', 'role_permission.role_id')
        ->join('permissions', 'role_permission.permission_id', '=', 'permissions.id')
        ->where('admin_role.admin_id', $this->id)
        ->where('permissions.slug', $permissionSlug)
        ->exists();
}

    public function department()
    {
        return $this->belongsTo(Department::class, 'depart_id', 'id');
    }
}