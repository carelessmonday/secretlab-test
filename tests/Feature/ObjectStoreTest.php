<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObjectStoreTest extends TestCase {

    use RefreshDatabase;

    public function test_object_store()
    {
        $key = 'testkey';
        $value = 'testvalue';
        $response = $this->postJson('/api/object', [
            $key => $value
        ]);

        $response->assertJson(['success' => TRUE])
            ->assertStatus(200);

        $this->assertDatabaseHas('objects', [
            'key' => $key,
        ])->assertDatabaseHas('object_values', [
            'object_key' => $key,
        ]);
    }
}
