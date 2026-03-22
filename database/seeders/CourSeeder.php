<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cour;
use App\Models\User;

class CourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = User::factory()->teacher()->count(5)->create();

        $teachers->each(function ($teacher) {
            Cour::factory()->count(3)->create([
                'teacher_id' => $teacher->id,
            ]);
        });

    }
}
