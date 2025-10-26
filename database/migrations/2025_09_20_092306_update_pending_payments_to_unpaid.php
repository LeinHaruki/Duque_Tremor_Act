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
        // Update any existing "Pending" payments to "Unpaid"
        DB::table('payments')
            ->where('status', 'Pending')
            ->update(['status' => 'Unpaid']);
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
