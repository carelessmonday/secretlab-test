<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObjectGetTest extends TestCase {

    use RefreshDatabase;

    public function test_object_get()
    {
        $key = 'testkey';
        $value = 'testvalue';
        $this->postJson('/api/object', [
            $key => $value
        ]);

        $response = $this->getJson("/api/object/{$key}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'key',
                    'value',
                    'timestamp'
                ]
            ])
            ->assertJson(['data' => [
                'key'   => $key,
                'value' => $value
            ]]);
    }

    public function test_returns_404_if_not_existing_object()
    {
        $response = $this->getJson("/api/object/inoexist");

        $response->assertStatus(404)
            ->assertJsonStructure(['message'])
            ->assertJson(['message' => 'Resource not found.']);
    }

    public function test_object_get_with_timestamp()
    {
        $timestamp = now()->unix();
        $key = 'testkey';
        $value = 'testvalue';
        $this->postJson('/api/object', [
            $key => $value
        ]);
        $response = $this->getJson("/api/object/{$key}?timestamp=$timestamp");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'key',
                    'value',
                    'timestamp'
                ]
            ])
            ->assertJson([
                'data' => [
                    'key'   => $key,
                    'value' => $value
                ]
            ]);
    }

    public function test_returns_empty_index()
    {
        $response = $this->getJson("/api/object/get_all_records");
        $response->assertStatus(200)
            ->assertJsonCount(0, 'data')
            ->assertJsonStructure(['data' => []]);
    }

    public function test_returns_object_collection()
    {
        $data = [
            ['testkey1' => 'testvalue1'],
            ['testkey2' => 'testvalue2']
        ];

        foreach ($data as $item) {
            $this->postJson('/api/object', $item);
        }

        $response = $this->getJson("/api/object/get_all_records");
        $response->assertStatus(200)
            ->assertJsonCount(count($data), 'data')
            ->assertJsonStructure(['data' => []]);
    }
}
