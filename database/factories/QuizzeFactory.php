<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseContent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quizze>
 */
class QuizzeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(), 
            'content_id' => CourseContent::factory(), 
            'type' => $this->faker->randomElement(['lesson', 'final', 'unlock']),
            'total_point' => $this->faker->numberBetween(10, 100),
            'title' => $this->faker->sentence(3),
        ];
    }
}
