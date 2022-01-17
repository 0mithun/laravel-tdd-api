<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TodoList;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private $list;

    public function setUp(): void
    {
        parent::setUp();
        $this->authUser();
        $this->list = $this->createTodoList();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fetch_all_todo_list()
    {
       //Prepare
    //    $lists = TodoList::factory()->create();


       //Perform
        $response = $this->getJson(route('todo-lists.index'));


       //Predict
       $this->assertEquals(1, count($response->json()));
    }


    public function test_single_todo_list()
    {
        //Prepare
        // $list = TodoList::factory()->create();
        //Perform
        $response = $this->getJson(route('todo-lists.show', $this->list->id))
            ->assertOk()
            ->json();

        $this->assertEquals($this->list->name, $response['name']);
        // $this->assertEquals(1, count($response));
    }


    public function test_store_new_todo_list()
    {
        //Preparation
        $list = TodoList::factory()->make();

        //Action
        $response = $this->postJson(route('todo-lists.store'), ['name'=> $list->name])
            ->assertCreated()
            ->json()
            ;

        //Assertion
        $this->assertEquals($list->name, $response['name']);
        $this->assertDatabaseHas('todo_lists', ['name'=> $list->name]);
    }


    public function test_while_store_todo_list_name_field_is_required()
    {
        //Preparation
        $this->withExceptionHandling();
        //Action
        $response = $this->postJson(route('todo-lists.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name')
            ;
        //Assertion
    }

    public function test_delete_todo_list()
    {
        //Preparation

        //Action
        $this->deleteJson(route('todo-lists.destroy', $this->list->id))
            ->assertNoContent();

        //Assertion
        $this->assertDatabaseMissing('todo_lists', ['name'=> $this->list->name]);
    }

    public function test_update_todo_list()
    {
        $this->patchJson(route('todo-lists.update', $this->list->id), ['name'=>'Updated List'])
            ->assertOk();

        $this->assertDatabaseMissing('todo_lists', ['name'=>   $this->list->name]);
        $this->assertDatabaseHas('todo_lists', ['name'=>'Updated List']);
    }



    public function test_while_upate_todo_list_name_field_is_required()
    {
        //Preparation
        $this->withExceptionHandling();
        //Action
        $this->patchJson(route('todo-lists.update', $this->list->id))
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('name')
            ;
        //Assertion
    }
}
