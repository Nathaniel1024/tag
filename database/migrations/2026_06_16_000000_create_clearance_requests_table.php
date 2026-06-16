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
            $table->string('ref', 100)->unique();
            $table->string('owner_key', 255)->nullable()->index();
            $table->string('owner_name', 255)->nullable();
            $table->string('owner_email', 255)->nullable()->index();
            $table->string('name', 255);
            $table->string('email', 255)->index();
            $table->string('address', 500);
            $table->string('age', 20)->nullable();
            $table->string('contact', 50)->nullable();
            $table->string('purpose', 255);
            $table->string('purpose_reason', 500);
            $table->string('status', 20)->default('pending')->index();
            $table->date('date_requested')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('id_file_name')->nullable();
            $table->string('id_file_path')->nullable();
            $table->string('id_file_mime', 100)->nullable();
            $table->boolean('pdf_saved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clearance_requests');
    }
};
