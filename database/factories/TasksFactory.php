<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tanak\TaskManager\Models\Administrator;
use Tanak\TaskManager\Models\Process;
use Tanak\TaskManager\Models\Project;
use Tanak\TaskManager\Models\Tasks;

class TasksFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tasks::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
            'user_id' => Administrator::factory(),
            'tm_project_id' => null,
            'tm_process_id' => null,
            'assigner_id' => null,
        ];
    }
    public function configure()
    {
        return $this->afterMaking(function (Tasks $task) {
            $project = Project::all()->random();
            $task->tm_project_id = $project->id;
            $process = $project->process()->inRandomOrder()->first();
            $task->tm_process_id = $process->id;
            $task->assigner_id = Administrator::all()->random()->id;
            
        })->afterCreating(function (Tasks $task) {
            $task->save();
        });
    }
}
