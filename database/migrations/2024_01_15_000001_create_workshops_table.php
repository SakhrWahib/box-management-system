<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('workshop_activity');
            $table->string('name');
            $table->string('workshop_number');
            $table->string('email');
            $table->string('manager_name');
            $table->string('owner_name');
            $table->string('commercial_record');
            $table->text('location');
            $table->string('bank_name');
            $table->string('iban');
            $table->text('records')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
}; 