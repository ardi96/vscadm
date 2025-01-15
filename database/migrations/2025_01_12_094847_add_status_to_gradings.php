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
        Schema::table('gradings', function (Blueprint $table) {
            $table->string('status',10)->default('pending'); //draft, pending, approved
            $table->foreignId('grade_id'); 
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gradings', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('grade_id');
            $table->dropColumn('approved_at');
            $table->dropColumn('approved_by');
        });
    }
};
