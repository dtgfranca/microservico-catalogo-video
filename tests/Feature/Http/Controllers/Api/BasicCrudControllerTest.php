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
        CategoryStub::dropTable();
        parent::tearDown();

    }

    // public function testIndex()
    // {
    //     categoryStub::create(['name'=>'test_name', 'description'=>'description']);
    //     $category = new CategoryControllerStub();
    //     $this->assertEquals([$category->toArray], $category->index());
    // }

}
