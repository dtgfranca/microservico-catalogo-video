<?php

namespace Tests\Stubs\Models;

use App\Models\Traits\Uuid as TraitsUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CategoryStub extends Model
{
    //
    protected $table = 'category_stubs';
    protected $fillable = ['name', 'description'];

    public static function createTable()
    {
        Schema::create('categories_stubs', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

    }
    public static function dropTable()
    {
        Schema::dropIfExists('categories_stubs');

    }

}

