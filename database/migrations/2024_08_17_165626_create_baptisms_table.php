<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('baptisms', function (Blueprint $table) {
            $table->id();
            $table->char('user_id', 36);
            $table->char('baptized_by', 36);
            $table->unsignedBigInteger('church_id');
            $table->date('baptized_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('baptized_by')->references('id')->on('users');
            $table->foreign('church_id')->references('id')->on('churches');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baptisms');
    }
};
