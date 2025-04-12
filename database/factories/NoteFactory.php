<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'content' => $this->faker->text,
            'class_id' => $this->faker->uuid,
        ];
    }

    public function forUser(User $user)
    {
        return $this->state(function() use ($user){
            return [
                'user_id' => $user->getKey()
            ];
        });
    }
}
