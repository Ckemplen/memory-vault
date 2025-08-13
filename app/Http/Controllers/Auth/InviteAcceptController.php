<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\AcceptInvitationAction;
use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class InviteAcceptController extends Controller
{
    public function __construct(private AcceptInvitationAction $acceptInvitation) {}

    public function show(string $token): View
    {
        $inv = Invitation::query()->where('token', $token)->first();
        if (! $inv || ! $inv->canUse()) {
            abort(404);
        }

        return view('auth.invite-accept', ['inv' => $inv]);
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        try {
            $user = $this->acceptInvitation->execute($token, $data);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }

        Auth::login($user);

        return redirect()->intended(route('home'));
    }
}
