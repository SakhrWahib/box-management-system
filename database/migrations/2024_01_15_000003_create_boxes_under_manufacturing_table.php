<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boxes_under_manufacturing', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->foreignId('workshop_id')->constrained('workshops')->onDelete('restrict');
            $table->foreignId('box_type_id')->constrained('box_types')->onDelete('restrict');
            $table->integer('quantity');
            $table->integer('received_quantity')->default(0);
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->decimal('remaining_amount', 10, 2)->default(0);
            $table->date('order_date');
            $table->date('actual_delivery_date')->nullable();
            $table->string('barcode')->unique()->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boxes_under_manufacturing');
    }
}; 