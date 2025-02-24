<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoxUnderManufacturing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'boxes_under_manufacturing';

    protected $fillable = [
        'invoice_number',
        'workshop_id',
        'box_type_id',
        'quantity',
        'received_quantity',
        'unit_price',
        'paid_amount',
        'remaining_amount',
        'order_date',
        'actual_delivery_date',
        'barcode',
        'notes'
    ];

    protected $casts = [
        'order_date' => 'date',
        'actual_delivery_date' => 'date',
        'quantity' => 'integer',
        'received_quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'barcode' => 'string'
    ];

    // العلاقة مع الورشة
    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    // العلاقة مع نوع الصندوق
    public function boxType()
    {
        return $this->belongsTo(BoxType::class);
    }

    // حساب المبلغ المتبقي وإنشاء رقم الفاتورة
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // حساب المبلغ المتبقي فقط إذا تم تحديد سعر الوحدة
            if ($model->unit_price) {
                $totalPrice = $model->quantity * $model->unit_price;
                $model->remaining_amount = $totalPrice - ($model->paid_amount ?? 0);
            } else {
                $model->remaining_amount = 0;
            }
        });

        static::updating(function ($model) {
            // حساب المبلغ المتبقي فقط إذا تم تحديد سعر الوحدة
            if ($model->unit_price) {
                $totalPrice = $model->quantity * $model->unit_price;
                $model->remaining_amount = $totalPrice - ($model->paid_amount ?? 0);
            }
        });
    }

    // أضف هذه الدالة للتأكد من قيمة unit_price
    public function getUnitPriceAttribute($value)
    {
        return $value ?? 0;
    }
} 