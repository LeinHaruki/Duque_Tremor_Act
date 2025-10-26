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
        // Update existing appointments with null purpose to have a default purpose
        DB::table('appointments')
            ->whereNull('purpose')
            ->update(['purpose' => 'General consultation']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be easily reversed as we're modifying existing data
        // The down method is left empty intentionally
    }
};
