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
        Schema::table('transfer_requests', function (Blueprint $table) {
            $table->enum('reason', [
                'Geographical Relocation',
                'Theological Differences',
                'Family Reasons',
                'Work',
                'Church Leadership and Management',
                'Other',
            ])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_requests', function (Blueprint $table) {
            //
        });
    }
};
