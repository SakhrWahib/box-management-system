<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->foreignId('box_type_id')->constrained('box_types');
            $table->integer('received_quantity');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_boxes');
    }
}; 