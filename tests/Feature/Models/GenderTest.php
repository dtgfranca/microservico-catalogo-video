<?php

namespace Tests\Feature\Models;

use App\Models\Gender;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenderTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Gender::class, 1)->create();
        $genders = Gender::all();
        $gendersKeys = array_keys($genders->first()->getAttributes());
        $this->assertCount(1, $genders);
        $this->assertEqualsCanonicalizing([
            'id',
            'name',
            'is_active',
            'created_at',
            'updated_at',
            'deleted_at'
        ], $gendersKeys);
    }
    public function testCreate()
    {
        $gender = Gender::create([
            'name' => 'Terror',

        ]);
        $gender->refresh();
        $this->assertEquals('Terror', $gender->name);
        $this->assertTrue($gender->is_active);

        $gender = Gender::create([
            'name' => 'Terror',
            'is_active' =>false
        ]);
        $this->assertFalse($gender->is_active);

        $gender = Gender::create([
            'name' => 'Terror',
        ]);
        $this->assertTrue($this->isValidUuid($gender->id));

    }
    public function testUpdate()
    {
        $gender = factory(Gender::class)->create([
            'name'=>'Suspense'
        ]);
        $data = [
            'name' =>'Comédia',
            'is_active'=>false
        ];
        $gender->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $gender->{$key});
        }
    }
    public function testDelete()
    {
        $gender = factory(Gender::class)->create([
            'name'=>'Suspense'
        ]);
        $gender->delete();

        $this->assertSoftDeleted('genders',['deleted_at'=>$gender->deleted_at, 'id'=> $gender->id]);
    }
    private function isValidUuid( $uuid ) {

        if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
            return false;
        }

        return true;
    }
}
