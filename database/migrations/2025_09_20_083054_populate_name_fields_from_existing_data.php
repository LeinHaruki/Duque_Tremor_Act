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
        // Populate doctors table
        $doctors = DB::table('doctors')->whereNotNull('name')->get();
        foreach ($doctors as $doctor) {
            $nameParts = explode(' ', trim($doctor->name), 3);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[2] ?? ($nameParts[1] ?? '');
            $middleInitial = isset($nameParts[1]) && strlen($nameParts[1]) === 1 ? $nameParts[1] : null;
            
            DB::table('doctors')
                ->where('id', $doctor->id)
                ->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'middle_initial' => $middleInitial
                ]);
        }
        
        // Populate patients table
        $patients = DB::table('patients')->whereNotNull('name')->get();
        foreach ($patients as $patient) {
            $nameParts = explode(' ', trim($patient->name), 3);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[2] ?? ($nameParts[1] ?? '');
            $middleInitial = isset($nameParts[1]) && strlen($nameParts[1]) === 1 ? $nameParts[1] : null;
            
            DB::table('patients')
                ->where('id', $patient->id)
                ->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'middle_initial' => $middleInitial
                ]);
        }
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
