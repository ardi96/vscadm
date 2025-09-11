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
        Schema::create('global_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('parameter_key')->unique();
            $table->string('description')->nullable();
            $table->string('string_value', 255)->nullable();
            $table->integer('int_value')->nullable();
            $table->boolean('bool_value')->nullable();
            $table->date('date_value')->nullable();
            $table->decimal('decimal_value', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_parameters');
    }
};
