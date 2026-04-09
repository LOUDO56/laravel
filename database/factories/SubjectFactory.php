<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    private static array $subjects = [
        'Développement Web', 'Base de données', 'Algorithmes',
        'Réseaux informatiques', 'Sécurité applicative', 'DevOps',
        'JavaScript avancé', 'PHP & Laravel', 'UX Design',
        'Gestion de projet', 'API REST', 'Cloud Computing',
    ];

    public function definition(): array
    {
        return [
            'name'           => fake()->unique()->randomElement(self::$subjects),
            'description'    => fake()->paragraph(),
            'expected_hours' => fake()->numberBetween(20, 60),
            'school_id'      => School::factory(),
        ];
    }
}
