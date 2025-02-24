<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
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

    // العلاقة مع الصناديق في المخزون
    public function inventoryBoxes()
    {
        return $this->hasMany(InventoryBox::class);
    }

    // العلاقة مع الصناديق المباعة
    public function soldBoxes()
    {
        return $this->hasMany(SoldBox::class);
    }

    // العلاقة مع الصناديق المحجوزة
    public function reservedBoxes()
    {
        return $this->hasMany(ReservedBox::class);
    }

    // العلاقة مع الصناديق في الصيانة
    public function maintenanceBoxes()
    {
        return $this->hasMany(MaintenanceBox::class);
    }
} 