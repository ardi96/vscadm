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
        Schema::create('reactivation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('member_id')->constrained('members');
            $table->integer('status')->default(0); // 0 : pending, 1 : approve, 2: rejected
            $table->decimal('amount',12,2)->default(0); // amount registration + 1st month fee
            $table->string('notes')->nullable();
            $table->string('bank',44);
            $table->string('file_name');
            $table->foreignId('approver_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reactivation_requests');
    }
};
