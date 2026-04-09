<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\Content;
use App\Models\School;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer 2 écoles avec leurs données complètes
        foreach (['Alpha Academy', 'Beta Institut'] as $schoolName) {
            $school = School::create([
                'name'        => $schoolName,
                'description' => "École de formation numérique — $schoolName",
                'address'     => '12 rue du Numérique, Paris',
            ]);

            // Admin école
            $admin = User::factory()->admin()->create([
                'name'      => "Admin $schoolName",
                'email'     => strtolower(str_replace(' ', '.', $schoolName)) . '@admin.test',
                'password'  => bcrypt('password'),
                'school_id' => $school->id,
            ]);

            // 2 formateurs
            $teachers = User::factory()->teacher()->count(2)->create([
                'school_id' => $school->id,
                'password'  => bcrypt('password'),
            ]);

            // 5 étudiants
            $students = User::factory()->student()->count(5)->create([
                'school_id' => $school->id,
                'password'  => bcrypt('password'),
            ]);

            // 2 matières
            $subjects = collect();
            $subjectNames = ['Développement Web', 'Base de données'];
            foreach ($subjectNames as $subjectName) {
                $subjects->push(Subject::create([
                    'name'           => $subjectName,
                    'description'    => "Cours de $subjectName — $schoolName",
                    'expected_hours' => 35,
                    'school_id'      => $school->id,
                ]));
            }

            // 2 classes
            for ($i = 1; $i <= 2; $i++) {
                $classroom = Classroom::create([
                    'name'      => "Classe B3-0$i — $schoolName",
                    'school_id' => $school->id,
                ]);

                // Attacher les formateurs à la classe
                foreach ($teachers as $teacher) {
                    $classroom->users()->attach($teacher->id, ['role_in_class' => 'teacher']);
                }

                // Répartir les étudiants sur les classes (tous dans toutes les classes)
                foreach ($students as $student) {
                    $classroom->users()->attach($student->id, ['role_in_class' => 'student']);
                }

                // Lier les matières aux classes
                foreach ($subjects as $subject) {
                    $classroom->subjects()->attach($subject->id);
                }
            }

            // 3 contenus par matière, créés par le premier formateur
            foreach ($subjects as $subject) {
                $videoTitles = [
                    "Introduction à {$subject->name}",
                    "Concepts avancés — {$subject->name}",
                    "Projet pratique — {$subject->name}",
                ];
                foreach ($videoTitles as $order => $title) {
                    Content::create([
                        'subject_id'       => $subject->id,
                        'teacher_id'       => $teachers->first()->id,
                        'title'            => $title,
                        'description'      => "Description du cours : $title",
                        'video_url'        => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                        'duration_seconds' => 1800,
                        'order'            => $order,
                    ]);
                }
            }
        }

        // Compte de démonstration facile à utiliser
        $demoSchool = School::first();
        User::factory()->admin()->create([
            'name'      => 'Demo Admin',
            'email'     => 'admin@demo.test',
            'password'  => bcrypt('password'),
            'school_id' => $demoSchool->id,
        ]);
        User::factory()->teacher()->create([
            'name'      => 'Demo Teacher',
            'email'     => 'teacher@demo.test',
            'password'  => bcrypt('password'),
            'school_id' => $demoSchool->id,
        ]);
        User::factory()->student()->create([
            'name'      => 'Demo Student',
            'email'     => 'student@demo.test',
            'password'  => bcrypt('password'),
            'school_id' => $demoSchool->id,
        ]);
    }
}
