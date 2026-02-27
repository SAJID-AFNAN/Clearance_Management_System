<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('employee_id', 50)->unique();
            $table->unsignedTinyInteger('department_id');
            $table->string('designation', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('signature_path')->nullable();
            $table->boolean('is_hall_warden')->default(false);
            $table->boolean('is_librarian')->default(false);
            $table->boolean('is_lab_incharge')->default(false);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments');
            
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};