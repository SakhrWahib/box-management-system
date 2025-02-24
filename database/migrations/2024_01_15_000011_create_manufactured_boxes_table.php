<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('manufactured_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->foreignId('workshop_id')->constrained('workshops');
            $table->foreignId('box_type_id')->constrained('box_types');
            $table->integer('quantity');
            $table->integer('received_quantity');
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->date('order_date');
            $table->date('actual_delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('manufactured_boxes');
    }
}; 