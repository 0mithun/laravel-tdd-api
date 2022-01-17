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
        $this->authUser();
    }


    public function test_fetch_all_tasks_of_a_todo_list()
    {
        $list = $this->createTodoList();

        $task = $this->createTask(['todo_list_id'=>$list->id]);

        $newTtask = $this->createTask(['todo_list_id'=>2]);

        $response = $this->getJson(route('todo-lists.tasks.index', $list->id))
            ->assertOk()
                ->json()

            ;

        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title, $response[0]['title']);
        $this->assertEquals($list->id, $response[0]['todo_list_id']);
    }

    public function test_store_a_task_for_a_todo_list()
    {
        $list = $this->createTodoList();
        $task = Task::factory()->make();
        $label = $this->createLabel();

        $response = $this->postJson(route('todo-lists.tasks.store', $list->id), ['title'=> $task->title, 'label_id' => $label->id])
            ->assertCreated()
            ->json()
            ;

        $this->assertEquals($task->title, $response['title']);
        $this->assertDatabaseHas('tasks', ['title'=>$task->title, 'todo_list_id'=>$list->id]);
        $this->assertCount(1, Task::all());

    }


    public function test_delete_a_task_from_database()
    {
        $list = $this->createTodoList();
        $task =  $this->createTask();
        $this->deleteJson(route('tasks.destroy',[ $list->id, $task->id]))
            ->assertNoContent()
        ;

        $this->assertDatabaseMissing('tasks', ['title', $task->title]);

        $this->assertCount(0, Task::all());
    }



    public function test_update_a_test_of_a_todo_list()
    {
        $list = $this->createTodoList();
        $task = $this->createTask();

        $response  = $this->patchJson(route('tasks.update', $task->id), ['title'=> 'New Title'])
            ->assertOk()
            ->json()
            ;

        $this->assertDatabaseMissing('tasks', ['title'=> $task->title]);
        $this->assertDatabaseHas('tasks', ['title'=>'New Title']);
        $this->assertEquals('New Title', $response['title']);
    }


    public function test_store_task_without_a_label_should_give_error()
    {

        $list = $this->createTodoList();
        $task = Task::factory()->make();

        $response = $this->postJson(route('todo-lists.tasks.store', $list->id), ['title'=> $task->title])
            ->assertCreated()
            // ->assertUnprocessable()
            // ->assertJsonValidationErrorFor('label_id')
            ;
    }
}
