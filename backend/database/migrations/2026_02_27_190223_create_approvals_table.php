<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clearance_request_id');
            $table->string('authority_type'); 
            $table->unsignedBigInteger('authority_id')->nullable(); 
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('comments')->nullable();
            $table->string('signature_path')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('clearance_request_id')->references('id')->on('clearance_requests')->onDelete('cascade');
            $table->foreign('authority_id')->references('id')->on('teachers')->onDelete('set null');

            $table->index('clearance_request_id');
            $table->index('status');
            $table->index('authority_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
