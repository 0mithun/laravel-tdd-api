<?php

namespace Tests;

use App\Models\Label;
use App\Models\Task;
use App\Models\User;
use App\Models\TodoList;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    public function setUp() :void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }


    public function createTodoList( array $args = [])
    {
        return TodoList::factory()->create($args);
    }

    public function createTask(array $args = [])
    {
        return Task::factory()->create($args);
    }

    public function createUser(array $args = []){
        return User::factory()->create($args);
    }

    public function createLabel(array $args = [])
    {
        return Label::factory()->create($args);
    }

    public function authUser()
    {
        $user = $this->createUser();
        Sanctum::actingAs($user);

        return $user;
    }
}
