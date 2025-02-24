<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryBox extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'box_type_id',
        'received_quantity',
        'notes'
    ];

    public function boxType()
    {
        return $this->belongsTo(BoxType::class);
    }
} 