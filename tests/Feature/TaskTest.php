<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{

    use RefreshDatabase;


    public function setUp(): void
    {
        parent::setUp();
    }


    public function test_fetch_all_tasks_of_a_todo_list()
    {
        $task = $this->createTask();
        $response = $this->getJson(route('tasks.index'))
            ->assertOk()
                ->json()

            ;

        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title, $response[0]['title']);
    }

    public function test_store_a_task_for_a_todo_list()
    {
        $task = Task::factory()->make();

        $response = $this->postJson(route('tasks.store'), ['title'=> $task->title])
            ->assertCreated()
            ->json()
            ;



        $this->assertEquals($task->title, $response['title']);
        $this->assertDatabaseHas('tasks', ['title'=>$task->title]);
        $this->assertCount(1, Task::all());

    }


    public function test_delete_a_task_from_database()
    {
        $task =  $this->createTask();
        $this->deleteJson(route('tasks.destroy', $task->id))
            ->assertNoContent()
        ;

        $this->assertDatabaseMissing('tasks', ['title', $task->title]);

        $this->assertCount(0, Task::all());
    }
}
