<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tanak\TaskManager\Models\Administrator;
use Tanak\TaskManager\Models\Process;
use Tanak\TaskManager\Models\Project;

class ProcessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Process::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Todo',
            'color' => $this->faker->hexColor,
            'sort_by' => 1,
            'tm_project_id' => Project::factory()
        ];
    }
}
