<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Invitation;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CreateInvitationAction
{
    public function __construct(private Invitation $invitation) {}

    /**
     * @param  array{email?:string,expires_in?:int,max_uses?:int,role?:string}  $data
     */
    public function execute(Authenticatable $user, array $data): Invitation
    {
        return $this->invitation->create([
            'inviter_id' => (int) $user->getAuthIdentifier(),
            'token' => Str::random(48),
            'email' => $data['email'] ?? null,
            'expires_at' => isset($data['expires_in']) ? Carbon::now()->addDays((int) $data['expires_in']) : null,
            'max_uses' => $data['max_uses'] ?? 1,
            'role' => $data['role'] ?? 'member',
        ]);
    }
}
