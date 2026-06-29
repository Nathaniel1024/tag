<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clearance_requests', function (Blueprint $table) {
            $table->string('saved_cert_type', 255)->nullable()->after('pdf_saved');
            $table->json('saved_template')->nullable()->after('saved_cert_type');
            $table->longText('saved_paper_html')->nullable()->after('saved_template');
        });
    }

    public function down(): void
    {
        Schema::table('clearance_requests', function (Blueprint $table) {
            $table->dropColumn(['saved_cert_type', 'saved_template', 'saved_paper_html']);
        });
    }
};
