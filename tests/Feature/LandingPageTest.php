<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class LandingPageTest extends TestCase
{
    public function test_it_serves_the_public_landing_login_to_guests(): void
    {
        $this->get('/')->assertStatus(200); // Breeze login view
    }
}

