<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'name' => $this->faker->Company,
            'document' => $this->faker->isbn13,
            'zipCode' => $this->faker->ean8,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'status' => $this->faker->boolean,
        ];
    }
}
