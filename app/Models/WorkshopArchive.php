<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkshopArchive extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'workshop_id',
        'box_type_id',
        'quantity',
        'received_quantity',
        'order_date',
        'actual_delivery_date',
        'notes',
        'archived_at'
    ];

    protected $casts = [
        'order_date' => 'date',
        'actual_delivery_date' => 'date',
        'archived_at' => 'datetime',
        'quantity' => 'integer',
        'received_quantity' => 'integer'
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function boxType()
    {
        return $this->belongsTo(BoxType::class);
    }
} 