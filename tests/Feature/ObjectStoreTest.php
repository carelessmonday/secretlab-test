<?php

namespace Tests\Feature;

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

    public function test_returns_422_on_invalid_object_value()
    {
        $key = 'testkey';
        $value = NULL;
        $response = $this->postJson('/api/object', [
            $key => $value
        ]);

        $response->assertJsonStructure(['message', 'errors'])
            ->assertJson(['message' => 'The given data was invalid.'])
            ->assertStatus(422);

        $this->assertDatabaseMissing('objects', [
            'key' => $key,
        ])->assertDatabaseMissing('object_values', [
            'object_key' => $key,
        ]);
    }

    public function test_returns_422_on_invalid_object_key()
    {
        $data = [
            ['%' => 'testvalue1'],
            ['' => 'testvalue2'],
            ['a' => 'testvalue2'],
            [123 => 'testvalue2']
        ];

        foreach ($data as $key => $value) {
            $response = $this->postJson('/api/object', [$key => $value]);
            $response->assertJsonStructure(['message', 'errors'])
                ->assertJson(['message' => 'The given data was invalid.'])
                ->assertStatus(422);

            $this->assertDatabaseMissing('objects', [
                'key' => $key,
            ])->assertDatabaseMissing('object_values', [
                'object_key' => $key,
            ]);
        }
    }
}
