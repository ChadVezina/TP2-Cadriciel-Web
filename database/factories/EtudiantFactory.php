<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Etudiant;
use App\Models\Ville;
use App\Models\User;

class EtudiantFactory extends Factory
{
    protected $model = Etudiant::class;

    public function definition(): array
    {
        $name = $this->faker->name();
        $email = $this->faker->unique()->safeEmail();

        return [
            'name' => $name,
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $email,
            'birthdate' => $this->faker->date(),
            'city_id' => Ville::inRandomOrder()->first()->id,
            // Create a dedicated user account for each student with matching credentials
            'user_id' => User::factory()->create([
                'name' => $name,
                'email' => $email,
            ])->id,
        ];
    }

    /**
     * Create a student with an existing user account.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $user->name,
            'email' => $user->email,
            'user_id' => $user->id,
        ]);
    }
}
