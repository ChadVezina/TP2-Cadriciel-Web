<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, we need to remove duplicate cities
        // Keep only the first occurrence of each city name
        $duplicates = DB::table('villes')
            ->select('name', DB::raw('MIN(id) as min_id'))
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            // Update students that reference duplicate cities to use the first city
            DB::table('etudiants')
                ->where('city_id', '!=', $duplicate->min_id)
                ->whereIn('city_id', function ($query) use ($duplicate) {
                    $query->select('id')
                        ->from('villes')
                        ->where('name', $duplicate->name);
                })
                ->update(['city_id' => $duplicate->min_id]);

            // Delete all duplicate cities except the first one
            DB::table('villes')
                ->where('name', $duplicate->name)
                ->where('id', '!=', $duplicate->min_id)
                ->delete();
        }

        // Now add the unique constraint if it doesn't already exist
        $existing = DB::select("SHOW INDEX FROM `villes` WHERE Key_name = 'villes_name_unique'");
        if (empty($existing)) {
            Schema::table('villes', function (Blueprint $table) {
                $table->unique('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the unique constraint if it exists
        $existing = DB::select("SHOW INDEX FROM `villes` WHERE Key_name = 'villes_name_unique'");
        if (!empty($existing)) {
            Schema::table('villes', function (Blueprint $table) {
                $table->dropUnique(['name']);
            });
        }
    }
};
