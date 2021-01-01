<?php

namespace Database\Seeders;

use Database\Factories\ProcessFactory;
use DB;
use Illuminate\Database\Seeder;
use Encore\Admin\Auth\Database\Menu;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Eloquent\Model;
use Tanak\TaskManager\Models\Actions;
use Tanak\TaskManager\Models\Administrator;
use Tanak\TaskManager\Models\Process;
use Tanak\TaskManager\Models\Project;
use Tanak\TaskManager\Models\Tasks;

class TaskManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Administrator::factory()->times(5)->create();


            Project::factory()->count(10)->has(Process::factory()->count(5)->state(new Sequence(
                ['name' => 'Todo'],
                ['name' => 'Process'],
                ['name' => 'Pedding'],
                ['name' => 'Completed'],
                ['name' => 'Close']
            )))
            ->has(
                Tasks::factory()->count(10)->has(Actions::factory()->count(5)->state(new Sequence(
                    ['is_read' => true],
                    ['is_read' => false],
                )))
            )->create();



        // Project::factory()->has(

        // )->count(60)->create();
    }
}
