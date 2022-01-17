<?php

namespace Database\Factories;

use App\Models\Label;
use App\Models\TodoList;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'         =>  $this->faker->sentence(2),
            'description'   =>  $this->faker->paragraph(),
            'todo_list_id'         =>  TodoList::factory()->create()->id,
            'label_id'      =>  Label::factory()->create()->id,
        ];
    }
}
