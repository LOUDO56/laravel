<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Content>
 */
class ContentFactory extends Factory
{
    private static array $youtubeVideos = [
        'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'https://www.youtube.com/watch?v=ScMzIvxBSi4',
        'https://www.youtube.com/watch?v=09839DpTctU',
    ];

    public function definition(): array
    {
        return [
            'subject_id'       => Subject::factory(),
            'teacher_id'       => User::factory()->teacher(),
            'title'            => fake()->sentence(5),
            'description'      => fake()->paragraph(),
            'video_url'        => fake()->randomElement(self::$youtubeVideos),
            'duration_seconds' => fake()->numberBetween(300, 3600),
            'order'            => fake()->numberBetween(0, 10),
        ];
    }
}
