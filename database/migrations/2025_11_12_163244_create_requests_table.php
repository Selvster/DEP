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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number', 50)->unique();
            $table->string('name');
            $table->string('national_id', 14);
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->foreignId('center_id')->constrained('centers')->onDelete('cascade');
            $table->foreignId('request_type_id')->constrained('request_types')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('request_statuses')->onDelete('cascade');
            $table->text('description');
            $table->json('documents')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('response_document')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for tracking
            $table->index('tracking_number');
            $table->index('national_id');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
