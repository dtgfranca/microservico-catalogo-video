<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $category;

    public function setUp(): void
    {
        parent::setUp();
        $this->category = factory(Category::class)->create();
    }

    public function testIndex()
    {

        $response = $this->get(route('categories.index'));

        $response->assertStatus(200)
            ->assertJson([$this->category->toArray()]);
    }
    public function testShow()
    {

        $response = $this->get(route('categories.show',['category'=>$this->category->id]));

        $response->assertStatus(200)
            ->assertJson($this->category->toArray());
    }
    public function testInvalidationData()
    {
        $data = [
            'name'=>'',
        ];
       $this->assertInvalidationInStoreAction($data, 'required');
       $this->assertInvalidationInUpdateAction($data, 'required');

       $this->assertInvalidationInStoreAction(['name'=> str_repeat('a', 256)], 'max.string', ['max' => 255]);
       $this->assertInvalidationInUpdateAction(['name'=> str_repeat('a', 256)], 'max.string', ['max' => 255]);

       $this->assertInvalidationInStoreAction(['is_active'=> 'a'], 'boolean');
       $this->assertInvalidationInUpdateAction(['is_active'=> 'a'], 'boolean');

    }

    public function testStore()
    {
        $data = [
            'name'=>'test'
        ];
        $this->assertStore($data, $data + ['description'=> null, 'is_active'=> true, 'deleted_at'=> null]);

        $data = [
            'name'=>'test',
            'description' =>'description',
            'is_active'=>false
        ];
        $this->assertStore($data, $data + ['description'=> 'description', 'is_active'=> false, 'deleted_at'=> null]);
    }
    public function testUpdate()
    {
        $this->category =  factory(Category::class)->create([
            'description'=>'description',
            'is_active'=>false
        ]);

        $data  = [
            'name' => 'test',
            'description'=>'test',
            'is_active'=> true
        ];
        $this->assertUpdate($data,$data+['deleted_at'=>null]);

        $data  = [
            'name' => 'test',
            'description'=>'',

        ];
        $this->assertUpdate($data,array_merge($data, ['description'=>null]));
    }

    public function testDelete()
    {

        $id = $this->category->id;

        $response = $this->deleteJson(route('categories.destroy',['category' => $id]));
        $response->assertStatus(204);

        $category = Category::find($id);
        $this->assertNull($category);



    }
    protected function routeStore()
    {
        return route('categories.store');
    }
    protected function routeUpdate()
    {
        return route('categories.update',['category'=>$this->category->id]);
    }
    protected function model()
    {
        return Category::class;
    }

}
