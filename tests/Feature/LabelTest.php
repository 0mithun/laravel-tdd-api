<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_create_new_label()
    {
        $this->authUser();

        $this->postJson(route('labels.store'), [
            'name'  =>  'My Label',
            'color' =>  'red'
        ])
        ->assertCreated();

        $this->assertDatabaseHas('labels',  [
            'name'  =>  'My Label',
            'color' =>  'red'
        ]);
    }
}
