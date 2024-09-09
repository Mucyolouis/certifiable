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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username')->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->string('mother_name');
            $table->string('father_name');
            $table->string('god_parent')->nullable();
            $table->foreignId('church_id')->constrained('churches')->nullable();
            $table->boolean('baptized')->default(false);
            $table->uuid('baptized_by')->nullable();
           // $table->foreign('baptized_by')->references('id')->on('users')->onDelete('set null');
            $table->foreignId('ministry_id')->nullable()->constrained('ministries');
            $table->boolean('active_status')->default(0);
            $table->enum('marital_status', ['single', 'married','widowed'])->default('single');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
