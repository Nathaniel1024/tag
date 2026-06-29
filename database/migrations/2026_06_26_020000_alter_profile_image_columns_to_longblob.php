<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE `resident_registration_requests` MODIFY `profile_image` LONGBLOB NULL');
        DB::statement('ALTER TABLE `users` MODIFY `profile_image` LONGBLOB NULL');
        DB::statement('ALTER TABLE `residents` MODIFY `profile_image` LONGBLOB NULL');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE `resident_registration_requests` MODIFY `profile_image` BLOB NULL');
        DB::statement('ALTER TABLE `users` MODIFY `profile_image` BLOB NULL');
        DB::statement('ALTER TABLE `residents` MODIFY `profile_image` BLOB NULL');
    }
};
