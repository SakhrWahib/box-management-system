<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('box_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // مكتمل، مصنعة، تحت التصنيع
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes(); // إضافة عمود deleted_at
        });

        // إضافة البيانات الأساسية
        DB::table('box_statuses')->insert([
            [
                'name' => 'تحت التصنيع',
                'description' => 'الصناديق لا تزال في مرحلة التصنيع',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'مصنعة جزئياً',
                'description' => 'تم استلام جزء من الكمية',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'مكتمل',
                'description' => 'تم استلام كامل الكمية',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('box_statuses');
    }
}; 