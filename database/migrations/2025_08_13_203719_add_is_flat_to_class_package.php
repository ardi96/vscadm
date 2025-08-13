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
        Schema::table('class_packages', function (Blueprint $table) {
            $table->boolean('is_flat')->default(false)->after('price_per_session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_packages', function (Blueprint $table) {
            $table->dropColumn('is_flat');
        });
    }
};
