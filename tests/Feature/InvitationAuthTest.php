<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class InvitationAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_blocks_home_for_guests(): void
    {
        $this->get('/home')->assertRedirect('/login');
    }

    public function test_admin_can_create_invitation_and_guest_can_register_via_token(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)
            ->post(route('admin.invitations.store'), ['expires_in' => 7, 'max_uses' => 1])
            ->assertSessionHas('status');

        $inv = Invitation::query()->latest()->first();

        auth()->logout();

        $this->get(route('invite.accept', $inv->token))->assertOk();

        $this->post(route('invite.accept.store', $inv->token), [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertRedirect(route('home'));

        $this->assertTrue(User::where('email', 'new@example.com')->exists());
        $inv->refresh();
        $this->assertSame(1, $inv->uses);
    }

    public function test_rejects_invalid_or_expired_token(): void
    {
        $this->get('/invite/' . Str::random(48))->assertNotFound();
    }
}

