<?php

namespace Tests\Unit;

use App\Services\EtudiantService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EtudiantServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EtudiantService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(EtudiantService::class);
    }

    /**
     * Test that pagination validation bounds work correctly.
     */
    public function test_pagination_size_is_bounded(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getValidatedPerPage');
        $method->setAccessible(true);

        // Test minimum bound
        $this->assertEquals(10, $method->invoke($this->service, 5));

        // Test maximum bound
        $this->assertEquals(100, $method->invoke($this->service, 200));

        // Test valid value
        $this->assertEquals(25, $method->invoke($this->service, 25));
    }
}
