<?php

namespace Database\Seeders;

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
