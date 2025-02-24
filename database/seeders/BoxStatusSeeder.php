<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BoxStatus;

class BoxStatusSeeder extends Seeder
{
    public function run()
    {
        BoxStatus::create([
            'name' => 'قيد التصنيع',
            'description' => 'الصندوق قيد التصنيع'
        ]);

        BoxStatus::create([
            'name' => 'مكتمل',
            'description' => 'تم استلام كامل الكمية'
        ]);
    }
} 