<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserDetail>
 */
class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
          'specialization'=>$this->faker->jobTitle(),
          'image'=>$this->faker->imageUrl(200, 200, 'people', true),
          'bio'=>$this->faker->paragraph()
        ];
    }
}
