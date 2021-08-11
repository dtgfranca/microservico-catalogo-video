<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid as TraitsUuid;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;
    private $category;

    public function setUp(): void
    {
        parent::setUp();
        $this->category = new Category();
    }
    public function testFillable()
    {
        $fillable = ['name', 'description', 'is_active'];
        $this->assertEquals( $fillable, $this->category->getFillable());
    }
    /**
     * @
     *
     * @return void
     */
    public function testCasts()
    {
        $casts = [
            'id' => 'string',
            'is_active'=>'boolean'
        ];
        $this->assertEquals($casts, $this->category->getCasts());
    }
    public function testincrementing()
    {
        $this->assertFalse($this->category->incrementing);
    }
    public function testDates()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];

        foreach($dates as $date) {
            $this->assertContains($date, $this->category->getDates());
        }
        $this->assertCount(count($dates), $this->category->getDates());
    }
    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            TraitsUuid::class
        ];
        $categoryTraits = array_keys(class_uses(Category::class));
        $this->assertEquals($traits,$categoryTraits);
    }
}
