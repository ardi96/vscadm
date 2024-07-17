<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('class_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name',40);
            $table->string('description',140);
            $table->unsignedSmallInteger('session_per_week')->nullable();
            $table->enum('type',['private','regular','per sesi']);
            $table->decimal('price',14,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_packages');
    }
};
