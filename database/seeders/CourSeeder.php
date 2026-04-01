<?php

namespace Database\Seeders;

use App\Models\Cour;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Cour::count() === 0) {
            $teachers = User::where('role', 'teacher')->get();

            if ($teachers->count() > 0) {
                $teachers->each(function ($teacher) {
                    Cour::factory()->count(3)->create([
                        'teacher_id' => $teacher->id,
                    ]);
                });
            }
        }
    }
}
