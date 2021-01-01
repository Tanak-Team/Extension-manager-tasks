<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tanak\TaskManager\Models\Administrator;
use Tanak\TaskManager\Models\Project;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'user_id' => Administrator::factory()
        ];
    }
}
