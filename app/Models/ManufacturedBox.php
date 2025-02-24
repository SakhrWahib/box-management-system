<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManufacturedBox extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'workshop_id',
        'box_type_id',
        'quantity',
        'received_quantity',
        'unit_price',
        'total_price',
        'order_date',
        'actual_delivery_date',
        'notes'
    ];

    protected $casts = [
        'order_date' => 'date',
        'actual_delivery_date' => 'date',
        'quantity' => 'integer',
        'received_quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->total_price = $model->quantity * $model->unit_price;
        });

        static::updating(function ($model) {
            $model->total_price = $model->quantity * $model->unit_price;
        });
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function boxType()
    {
        return $this->belongsTo(BoxType::class);
    }

    public function calculateTotalPrice()
    {
        return $this->quantity * $this->unit_price;
    }
} 