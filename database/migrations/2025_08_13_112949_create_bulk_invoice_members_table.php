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
        Schema::create('bulk_invoice_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bulk_invoice_id')->constrained('bulk_invoices');
            $table->foreignId('member_id')->constrained('members');
            $table->foreignId('invoice_id')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_invoice_members');
    }
};
