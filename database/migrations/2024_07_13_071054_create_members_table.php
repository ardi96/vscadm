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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('school_name',40)->nullable();
            $table->enum('gender',['L','P']);
            $table->date('date_of_birth');
            $table->string('parent_name',40);
            $table->string('parent_mobile_no',40);
            $table->foreignId('costume_size_id')->nullable();
            $table->string('costume_label',40)->nullable();
            $table->foreignId('marketing_source_id')->nullable();
            $table->string('marketing_source_other')->nullable();
            $table->string('instagram')->nullable();
            $table->foreignId('class_package_id');
            $table->date('start_date');
            $table->enum('status',['pending','active','inactive']);
            $table->date('last_invoice_date')->nullable();
            $table->date('last_payment_date')->nullable();
            $table->decimal('balance',9,2)->nullable();
            $table->foreignId('parent_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
