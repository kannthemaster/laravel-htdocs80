<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HealthzTest extends TestCase
{
    /** @test */
    public function it_reports_health_with_required_keys_and_ok_status(): void
    {
        Storage::fake('local');

        $response = $this->get('/healthz');

        $response->assertOk()
            ->assertJsonStructure(['database', 'storage', 'version', 'server_time']);

        $json = $response->json();

        $this->assertSame('ok', $json['database']);
        $this->assertSame('ok', $json['storage']);
        $this->assertIsString($json['version']);
        $this->assertIsString($json['server_time']);

        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T.*Z?$/', $json['server_time']);
    }
}
