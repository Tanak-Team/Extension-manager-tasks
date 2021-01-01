<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tanak\TaskManager\Models\Actions;
use Tanak\TaskManager\Models\Administrator;
use Tanak\TaskManager\Models\Process;
use Tanak\TaskManager\Models\Project;
use Tanak\TaskManager\Models\Tasks;

class ActionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Actions::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'comment' => $this->faker->text,
            'tm_process_id' => null,
        ];
    }
    public function configure()
    {
        return $this->afterMaking(function (Actions $action) {
            $Administrator = Administrator::all()->random();
            $action->user_id = $Administrator->id;
            $action->assigner_id = $Administrator->id;
           
            $project = $action->task()->first()->project()->first();
            $process = $project->process()->get()->random();
            $action->tm_process_id = $process->id;
        })->afterCreating(function (Actions $action) {
        });
    }
}
