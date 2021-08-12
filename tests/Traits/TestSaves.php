<?php

namespace  Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestSaves
{
    protected function assertStore(array $sendData, array $testDatabase, array $testJsonData=null)
    {

        $response = $this->postJson($this->routeStore(), $sendData);
        if($response->status() !== 201) {
            throw new \Exception("Response status must be 201, given {$response->status()}: \n {$response->content()}");
        }
        $this->assertInDatabase($response, $testDatabase);
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);
        return $response;


    }
    protected function assertUpdate(array $sendData, array $testDatabase, array $testJsonData=null)
    {
        $response = $this->putJson($this->routeUpdate(), $sendData);
        if($response->status() !== 200) {
            throw new \Exception("Response status must be 200, given {$response->status()}: \n {$response->content()}");
        }
        $this->assertInDatabase($response, $testDatabase);
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);
        return $response;

    }

    private function assertInDatabase(TestResponse $response, $testDatabase)
    {
        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDatabase + ['id'=> $response->json('id')]);
    }

    private function assertJsonResponseContent(TestResponse $response, $testDatabase, $testJsonData)
    {
        $testResponse = $testJsonData?? $testDatabase;
        $response->assertJsonFragment($testResponse + ['id'=> $response->json('id')]);
    }
}
