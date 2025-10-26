<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\DoctorSpecialization;

class DoctorSpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample specializations
        $specializations = [
            'Cardiology',
            'Neurology',
            'Orthopedics',
            'Pediatrics',
            'Dermatology',
            'Gastroenterology',
            'Oncology',
            'Psychiatry',
            'Radiology',
            'Surgery',
            'Internal Medicine',
            'Emergency Medicine'
        ];

        // Create some sample doctors with multiple specializations
        $doctors = [
            [
                'name' => 'Dr. John Smith',
                'contact' => '+1234567890',
                'specializations' => ['Cardiology', 'Internal Medicine']
            ],
            [
                'name' => 'Dr. Sarah Johnson',
                'contact' => '+1234567891',
                'specializations' => ['Pediatrics', 'Emergency Medicine']
            ],
            [
                'name' => 'Dr. Michael Brown',
                'contact' => '+1234567892',
                'specializations' => ['Orthopedics', 'Surgery']
            ],
            [
                'name' => 'Dr. Emily Davis',
                'contact' => '+1234567893',
                'specializations' => ['Neurology', 'Psychiatry']
            ],
            [
                'name' => 'Dr. Robert Wilson',
                'contact' => '+1234567894',
                'specializations' => ['Dermatology', 'Oncology']
            ]
        ];

        foreach ($doctors as $doctorData) {
            $doctor = Doctor::create([
                'name' => $doctorData['name'],
                'contact' => $doctorData['contact']
            ]);

            foreach ($doctorData['specializations'] as $specialization) {
                DoctorSpecialization::create([
                    'doctor_id' => $doctor->id,
                    'specialization' => $specialization
                ]);
            }
        }
    }
}