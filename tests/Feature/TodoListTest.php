<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private $list;

    public function setUp(): void
    {
        parent::setUp();

        $this->list = TodoList::factory()->create();
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
        $response = $this->getJson(route('todo-list.index'));


       //Predict
       $this->assertEquals(1, count($response->json()));
    }


    public function test_single_todo_list()
    {
        //Prepare
        // $list = TodoList::factory()->create();
        //Perform
        $response = $this->getJson(route('todo-list.show', $this->list->id))
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
        $response = $this->postJson(route('todo-list.store'), ['name'=> $list->name])
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
        $response = $this->postJson(route('todo-list.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name')
            ;
        //Assertion

    }
}
