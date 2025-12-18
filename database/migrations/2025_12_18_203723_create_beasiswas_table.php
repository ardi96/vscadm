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
        Schema::create('beasiswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->date('start_date');
            $table->date('end_date');   
            $table->decimal('biaya', 12, 2)->default(0);
            $table->foreignId('created_by');
            $table->foreignId('approved_by')->nullable();
            $table->integer('status')->default(1); // 0: pending, 1: approved, 2: rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beasiswas');
    }
};
