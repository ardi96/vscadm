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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id');
            $table->foreignId('parent_id');
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->string('description',40);
            $table->string('item_description',140);
            $table->decimal('amount',14,2);
            $table->enum('type',['registration','membership']);
            $table->enum('status',['unpaid','pending','paid','void']);
            $table->dateTime('payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
