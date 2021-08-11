<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations;

    public function testIndex()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.index'));

        $response->assertStatus(200)
            ->assertJson([$category->toArray()]);
    }
    public function testShow()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.show',['category'=>$category->id]));

        $response->assertStatus(200)
            ->assertJson($category->toArray());
    }
    public function testInvalidationData()
    {
        $data = [
            'name'=>'',
        ];
       $this->assertInvalidationInStoreAction($data, 'required');

       $this->assertInvalidationInStoreAction(['name'=> str_repeat('a', 256)], 'max.string', ['max' => 255]);
       $this->assertInvalidationInStoreAction(['is_active'=> 'a'], 'boolean');



        $category = factory(Category::class)->create();

        $response =  $this->putJson(route('categories.update', ['category'=> $category->id]),  []);
        $this->assertInvalidationRequired($response);

        $response =  $this->putJson(route('categories.update',  ['category'=> $category->id]), [
            'name'=> str_repeat('a', 256),
            'is_active'=> 'a'
        ]);
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);



    }

    protected function assertInvalidationRequired(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['name'],'required',[]);
        $response
        ->assertJsonMissingValidationErrors(['is_active']);

    }

    protected function assertInvalidationMax(TestResponse $response) {

        $this->assertInvalidationFields($response, ['name'],'max.string',['max'=>255]);

    }
    protected function assertInvalidationBoolean(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['is_active'],'boolean',[]);
    }
    public function testStore()
    {
        $response = $this->postJson(route('categories.store'), [
            'name' => 'test'
        ]);
        $id = $response->json('id');
        $category = Category::find($id);
        $response->assertStatus(201)
        ->assertJson($category->toArray());
        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));

        $response = $this->postJson(route('categories.store'), [
            'name' => 'test',
            'is_active'=>false,
            'description'=>'description'
        ]);
        $id = $response->json('id');

        $response->assertJsonFragment([
            'is_active'=>false,
            'description'=>'description'
        ]);

    }
    public function testUpdate()
    {
        $category =  factory(Category::class)->create([
            'description'=>'description',
            'is_active'=>false
        ]);
        $response = $this->putJson(route('categories.update', ['category'=> $category->id]), [
            'name' => 'test',
            'description'=>'test',
            'is_active'=> true
        ]);
        $id = $response->json('id');
        $category = Category::find($id);

        $response->assertStatus(200)
        ->assertJson($category->toArray())
        ->assertJsonFragment([
            'description'=>'test',
            'is_active'=> true
        ]);

        $response = $this->putJson(route('categories.update', ['category'=> $category->id]), [
            'name' => 'test',
            'description'=>'',
            'is_active'=> true
        ]);
        $id = $response->json('id');
        $category = Category::find($id);
        $response->assertJsonFragment([
            'description'=> null
        ]);



    }
    public function testDelete()
    {
        $category = factory(Category::class)->create();
        $id = $category->id;

        $response = $this->deleteJson(route('categories.destroy',['category' => $id]));
        $response->assertStatus(204);

        $category = Category::find($id);
        $this->assertNull($category);



    }

    protected function routeStore()
    {
        return route('categories.store');
    }
}
