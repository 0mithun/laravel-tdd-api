<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Label;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->authUser();
    }

    public function test_a_user_can_create_new_label()
    {
        $label = Label::factory()->raw();

        $this->postJson(route('labels.store'), $label)
        ->assertCreated();

        $this->assertDatabaseHas('labels',  [
            'name'  =>  $label['name'],
            'color' =>  $label['color'],
        ]);
    }

    public function test_a_user_can_delete_a_label()
    {
        $label = $this->createLabel();

        $this->deleteJson(route('labels.destroy', $label->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('labels', ['id'=>$label->id]);
    }

    public function test_a_user_can_update_a_label()
    {
        $label = $this->createLabel();

        $this->patchJson(route('labels.update', $label->id), ['name'=>'New name', 'color'=>'New color'])
        ->assertOk()
        ;

        $this->assertDatabaseMissing('labels', ['name'=>$label->name]);
        $this->assertDatabaseMissing('labels', ['color'=>$label->color]);

        $this->assertDatabaseHas('labels', ['color'=>'New color', 'name'=>'New name']);
    }

    public function test_fetch_all_label_for_a_user()
    {
        $label = $this->createLabel(['user_id'=>$this->user->id]);
        $anotherLabel  = $this->createLabel();

        $response = $this->getJson(route('labels.index'))
            ->assertOk()
            ->json()
            ;

        $this->assertEquals($this->user->id, $response[0]['user_id']);
        $this->assertNotEquals($this->user->id, $anotherLabel->user_id);

    }
}
