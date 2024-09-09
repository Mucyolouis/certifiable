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
        Schema::create('marriages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('spouse1_id');
            $table->uuid('spouse2_id');
            $table->uuid('officiated_by')->nullable();
            $table->date('marriage_date');
            //$table->string('certificate_number')->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('spouse1_id')->references('id')->on('users');
            $table->foreign('spouse2_id')->references('id')->on('users');
            $table->foreign('officiated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marriages');
    }
};
