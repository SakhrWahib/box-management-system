<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshop_activity',
        'name',
        'workshop_number',
        'email',
        'manager_name',
        'owner_name',
        'commercial_record',
        'location',
        'bank_name',
        'iban',
        'records'
    ];

    protected $casts = [
        'rating' => 'decimal:2',
    ];

    // العلاقة مع الصناديق تحت التصنيع
    public function boxesUnderManufacturing()
    {
        return $this->hasMany(BoxUnderManufacturing::class);
    }

    // العلاقة مع الصناديق المصنعة
    public function manufacturedBoxes()
    {
        return $this->hasMany(ManufacturedBox::class);
    }

    public function getHasTransactionsAttribute()
    {
        return $this->boxesUnderManufacturing()->exists() || 
               $this->manufacturedBoxes()->exists();
    }
} 