<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_name',
        'mac_address',
        'user_id',
        'usercode',
        'site_data',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function codeList()
    {
        return $this->hasOne(CodeList::class);
    }
    public function events()
    {
        return $this->hasMany(Event::class);
    }
    public function lockfinger()
    {
        return $this->hasOne(Lockfinger::class);
    }
    public function indexdevice()
    {
        return $this->hasOne(indexlist::class);
    }
}
