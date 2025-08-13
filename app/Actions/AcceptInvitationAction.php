<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class AcceptInvitationAction
{
    public function __construct(private Invitation $invitations) {}

    /**
     * @param  array{name:string,email:string,password:string}  $data
     */
    public function execute(string $token, array $data): User
    {
        $inv = $this->invitations->newQuery()->where('token', $token)->lockForUpdate()->first();
        if (! $inv || ! $inv->canUse()) {
            throw new ModelNotFoundException;
        }

        if ($inv->email && strcasecmp($inv->email, $data['email']) !== 0) {
            abort(422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => $inv->role === 'admin',
        ]);

        $inv->increment('uses');
        if ($inv->uses >= $inv->max_uses) {
            $inv->used_at = now();
        }
        $inv->save();

        event(new Registered($user));

        return $user;
    }
}
