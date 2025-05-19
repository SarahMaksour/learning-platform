<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\CourseContent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discussion>
 */
class DiscussionFactory extends Factory
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
            'content_id' => CourseContent::factory(),      
            'parent_id' => null,                            
            'message' => $this->faker->paragraph(2),  
        ];
    }
}
