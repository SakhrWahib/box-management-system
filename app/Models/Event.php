<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'timestamp',
        'event_type',
        'method_type',
        'device_id', // Foreign key
    ];

    // Define the relationship with Device
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
