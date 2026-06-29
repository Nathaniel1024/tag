<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resident_registration_requests', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('fullname');
            $table->string('username');
            $table->string('email')->index();
            $table->string('password_hash');
            $table->string('contact')->nullable();
            $table->unsignedInteger('age');
            $table->string('address');
            $table->binary('profile_image')->nullable();
            $table->string('profile_image_mime', 100)->nullable();
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending')->index();
            $table->string('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('decision_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resident_registration_requests');
    }
};
