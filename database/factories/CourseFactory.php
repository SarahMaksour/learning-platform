<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), 
            'parent_course_id' => $this->faker->boolean(50) ? Course::factory() : null,
            'title' => $this->faker->sentence(4),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'is_free' => $this->faker->boolean(30), 
              'image'=>$this->faker->imageUrl(200, 200, 'course', true),
            'is_popular' => $this->faker->boolean(20), 
            'price' => $this->faker->randomFloat(2, 0, 200), 
            'description' => $this->faker->paragraph(4),
        ];
    }
}
