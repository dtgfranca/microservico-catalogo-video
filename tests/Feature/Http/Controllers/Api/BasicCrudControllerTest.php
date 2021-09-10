<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\Stubs\Controllers\CategoryControllerStub;
use Tests\Stubs\Models\CategoryStub;
use Tests\TestCase;

class BasicCrudContnrollerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        CategoryStub::createTable();
    }
    public function tearDown(): void
    {

        parent::tearDown();
        CategoryStub::dropTable();

    }

    public function testIndex()
    {
        $category = categoryStub::create(['name'=>'test_name', 'description'=>'description']);

        $controller = new CategoryControllerStub();

        $result = $controller->index()->toArray();
        $this->assertEquals([$category->toArray()], $result);
    }

}
