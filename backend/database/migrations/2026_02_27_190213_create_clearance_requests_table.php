<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clearance_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('request_no', 50)->unique();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected'])->default('pending');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('principal_approved_at')->nullable();
            $table->string('final_pdf_path')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            
            $table->index('student_id');
            $table->index('status');
            $table->index('request_no');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clearance_requests');
    }
};