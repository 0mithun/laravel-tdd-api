<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register()
    {
        $reponse = $this->postJson(route('user.register'), ['name'=>'Mithun Halder','email'=>'mithun@mail.com', 'password'=>'password','password_confirmation'=>'password'])
            ->assertCreated()
            ->json()
            ;

        $this->assertDatabaseHas('users', ['name'=>'Mithun Halder']);
        $this->assertEquals('Mithun Halder', $reponse['name']);
        $this->assertEquals('mithun@mail.com', $reponse['email']);
    }
}
