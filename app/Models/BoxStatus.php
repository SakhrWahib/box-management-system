<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoxStatus extends Model
{
    use SoftDeletes;

    protected $table = 'box_statuses';
    
    protected $fillable = [
        'name',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // العلاقة مع الصناديق تحت التصنيع
    public function boxesUnderManufacturing()
    {
        return $this->hasMany(BoxUnderManufacturing::class, 'status_id');
    }

    // دالة للحصول على حالة معينة بواسطة الاسم
    public static function getByName($name)
    {
        return self::where('name', $name)->first();
    }

    // دالة للتحقق ما إذا كانت الحالة "مكتمل"
    public function isCompleted()
    {
        return $this->name === 'مكتمل';
    }

    // دالة للتحقق ما إذا كانت الحالة "قيد التصنيع"
    public function isUnderManufacturing()
    {
        return $this->name === 'قيد التصنيع';
    }
} 