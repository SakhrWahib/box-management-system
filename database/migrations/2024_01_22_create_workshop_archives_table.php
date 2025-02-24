<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('workshop_archives', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->foreignId('workshop_id')->constrained('workshops')->onDelete('restrict');
            $table->foreignId('box_type_id')->constrained('box_types')->onDelete('restrict');
            $table->integer('quantity');
            $table->integer('received_quantity')->default(0);
            $table->date('order_date');
            $table->date('actual_delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('archived_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('workshop_archives');
    }
}; 