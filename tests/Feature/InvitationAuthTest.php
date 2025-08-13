<?php

declare(strict_types=1);

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

it('blocks /home for guests', function (): void {
    $this->get('/home')->assertRedirect('/login');
});

it('admin can create invitation and guest can register via token', function (): void {
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

    expect(User::where('email', 'new@example.com')->exists())->toBeTrue();
    $inv->refresh();
    expect($inv->uses)->toBe(1);
});

it('rejects invalid or expired token', function (): void {
    $this->get('/invite/'.Str::random(48))->assertNotFound();
});
