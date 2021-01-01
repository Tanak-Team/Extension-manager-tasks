<?php

namespace Database\Factories;

use Encore\Admin\Auth\Database\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Tanak\TaskManager\Models\Administrator;

class AdministratorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Administrator::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->userName,
            'password' => Hash::make('admin'),
            'name'     => $this->faker->name,
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Administrator $user) {

        })->afterCreating(function (Administrator $user) {
            $user->roles()->save(Role::first());
        });
    }
}
