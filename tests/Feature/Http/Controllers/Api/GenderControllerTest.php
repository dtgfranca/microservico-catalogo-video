<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Gender;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class GenderControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $gender = factory(Gender::class)->create();
        $response = $this->getJson(route('genders.index'));

        $response->assertStatus(200)
        ->assertJson([$gender->toArray()]);
    }
    public function testShow()
    {
        $gender = factory(Gender::class)->create();
        $response = $this->getJson(route('genders.show', ['gender'=>$gender->id]));

        $response->assertStatus(200)
        ->assertJson($gender->toArray());

    }

    public function testStore()
    {
        $response = $this->postJson(route('genders.store'), [
            'name'=> 'Terror',
            'is_active'=>true
        ]);
        $id = $response->json('id');
        $gender = Gender::find($id);

        $response->assertStatus(201)
        ->assertJson($gender->toArray());
        $this->assertTrue($response->json('is_active'));

        $response = $this->postJson(route('genders.store'), [
            'name'=> 'Terror1',
            'is_active'=>false
        ]);
        $id = $response->json('id');
        $gender = Gender::find($id);

        $response->assertStatus(201)
        ->assertJson($gender->toArray());
        $this->assertFalse($response->json('is_active'));

    }
    public function testInvalidationData()
    {
        $response = $this->postJson(route('genders.store'),[]);
        $this->assertInvalidationRequired($response);


        $gender = factory(Gender::class)->create();
        $response = $this->putJson(route('genders.update', ['gender'=>$gender->id]), []);
        $this->assertInvalidationRequired($response);

        $response = $this->putJson(route('genders.update', ['gender'=>$gender->id]), ['name'=>'teste', 'is_active'=>1213]);
        $this->assertInvalidationBoolean($response);

    }

    public function testUpdate()
    {
        $gender = factory(Gender::class)->create();

        $response = $this->putJson(route('genders.update',['gender'=>$gender->id]),
        [
            'name'=>'Comêdia Romântica',
            'is_active'=>true
        ]);
        $id = $response->json('id');
        $gender = Gender::find($id);

        $response->assertStatus(200)
        ->assertJson($gender->toArray());




    }

    public function testDelete()
    {
        $gender =  factory(Gender::class)->create();
        $id =  $gender->id;
        $response =  $this->deleteJson(route('genders.destroy',['gender'=> $id]));

        $response->assertStatus(204);

        $gender= Gender::find($id);
        $this->assertNull($gender);


    }
    protected function assertInvalidationRequired(TestResponse $response)
    {
        $response->assertStatus(422)
        ->assertJsonValidationErrors(['name'])
        ->assertJsonMissingValidationErrors(['is_active'])
        ->assertJsonFragment([
            Lang::get('validation.required', ['attribute'=>'name'])
        ]);

    }
    protected function assertInvalidationBoolean(TestResponse $response)
    {
        $response->assertStatus(422)
        ->assertJsonValidationErrors(['is_active'])
        ->assertJsonFragment([
            Lang::get('validation.boolean', ['attribute'=>'is active'])
        ]);
    }

}
