<?php

namespace Tests\Feature;

use Tests\TestCase;

class ModernDashboardTest extends TestCase
{
    /** @test */
    public function modern_dashboard_loads()
    {
        $this->get('/modern')->assertStatus(200);
    }
}
