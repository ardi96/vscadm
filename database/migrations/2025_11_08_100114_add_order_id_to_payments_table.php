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
        Schema::table('payments', function (Blueprint $table) {
            $table->boolean('is_online')->default(false)->after('id');
            $table->string('order_id', 64)->after('is_online')->nullable()->unique();
            $table->string('payment_url', 255)->after('order_id')->nullable();
            $table->string('file_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropColumn('payment_url');
            $table->dropColumn('is_online');
        });
    }
};
