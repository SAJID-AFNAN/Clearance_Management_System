<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('registration_no', 50)->unique();
            $table->string('session', 20);
            $table->unsignedTinyInteger('department_id');
            $table->unsignedTinyInteger('hall_id');
            $table->string('phone', 20);
            $table->string('photo_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->boolean('profile_completed')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('hall_id')->references('id')->on('halls');

            $table->index('registration_no');
            $table->index('department_id');
            $table->index('hall_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
