<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ObjectGetTest extends TestCase
{
    public function test_object_get()
    {
        $key = 'testkey';
        $value = 'testvalue';
        $this->postJson('/api/object', [
            $key => $value
        ]);

        $response = $this->get("/api/object/{$key}");

        $response->assertStatus(200);
        $content = $response->getContent();
        self::assertEquals($value, $content);
    }

    public function test_object_get_with_timestamp()
    {
        $timestamp = now()->unix();
        $key = 'testkey';
        $value = 'testvalue';
        $this->postJson('/api/object', [
            $key => $value
        ]);
        $response = $this->get("/api/object/{$key}?timestamp=$timestamp");

        $response->assertStatus(200);
        $content = $response->getContent();
        self::assertEquals($value, $content);
    }
}
