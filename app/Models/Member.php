<?php

namespace App\Models;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Activity;
use Illuminate\Support\Facades\Auth;

class Member extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    protected $table = 'members';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'username',
        'email',
        'password',
        'full_name',
        'phone',
        'status',
        'm_status',
        'date_join',
        'address',
        'company',
        'gender',
        'dateOfBirth',
        'avatar',
        'Tencongty',
        'Masothue',
        'Diachicongty',
        'Sdtcongty',
        'emailcty',
        'MaKH',
        'ward',
        'district',
        'city_province',
        'password_token'
    ];

    protected $hidden = [
        'password',
        'password_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Address::class, 'mem_id', 'id');
    }

    public function orderSum()
    {
        return $this->hasMany(OrderSum::class, 'mem_id', 'id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'member_id', 'id');
    }
    public function viewedNews()
    {
        return $this->belongsToMany(News::class, 'member_news_views');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('member_management');
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            $activity->causer_type = get_class($admin);
            $activity->causer_id = $admin->id;
        }

        $activity->properties = $activity->properties->merge([
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent')
        ]);
    }
}
