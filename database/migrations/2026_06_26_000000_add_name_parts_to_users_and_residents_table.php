<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function splitFullName(?string $fullName): array
    {
        $fullName = trim(preg_replace('/\s+/', ' ', (string) $fullName));

        if ($fullName === '') {
            return [
                'first_name' => null,
                'middle_name' => null,
                'last_name' => null,
            ];
        }

        $parts = preg_split('/\s+/', $fullName) ?: [];

        if (count($parts) === 1) {
            return [
                'first_name' => $parts[0],
                'middle_name' => null,
                'last_name' => null,
            ];
        }

        if (count($parts) === 2) {
            return [
                'first_name' => $parts[0],
                'middle_name' => null,
                'last_name' => $parts[1],
            ];
        }

        $firstName = array_shift($parts);
        $lastName = array_pop($parts);

        return [
            'first_name' => $firstName,
            'middle_name' => trim(implode(' ', $parts)) ?: null,
            'last_name' => $lastName,
        ];
    }

    private function backfillNameColumns(string $table, string $sourceColumn, bool $updateNameColumn = false): void
    {
        DB::table($table)
            ->select('id', $sourceColumn)
            ->orderBy('id')
            ->chunkById(100, function ($rows) use ($table, $sourceColumn, $updateNameColumn) {
                foreach ($rows as $row) {
                    $parts = $this->splitFullName($row->{$sourceColumn} ?? null);
                    $fullName = trim(implode(' ', array_filter([
                        $parts['first_name'],
                        $parts['middle_name'],
                        $parts['last_name'],
                    ]))) ?: null;

                    $update = [
                        'first_name' => $parts['first_name'],
                        'middle_name' => $parts['middle_name'],
                        'last_name' => $parts['last_name'],
                        'fullname' => $fullName,
                    ];

                    if ($updateNameColumn) {
                        $update['name'] = $fullName ?? ($row->{$sourceColumn} ?? null);
                    }

                    DB::table($table)
                        ->where('id', $row->id)
                        ->update($update);
                }
            }, 'id');
    }

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('fullname');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_name');
            $table->binary('profile_image')->nullable()->after('last_name');
            $table->string('profile_image_mime', 100)->nullable()->after('profile_image');
        });

        Schema::table('residents', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('fullname');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_name');
            $table->binary('profile_image')->nullable()->after('last_name');
            $table->string('profile_image_mime', 100)->nullable()->after('profile_image');
        });

        $this->backfillNameColumns('users', 'fullname', true);
        $this->backfillNameColumns('residents', 'fullname');
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_name', 'last_name', 'profile_image', 'profile_image_mime']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_name', 'last_name', 'profile_image', 'profile_image_mime']);
        });
    }
};
