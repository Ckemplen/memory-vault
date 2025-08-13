<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\CreateInvitationAction;
use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function __construct(private CreateInvitationAction $createInvitation) {}

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['nullable', 'email'],
            'expires_in' => ['nullable', 'integer', 'min:1', 'max:365'],
            'max_uses' => ['nullable', 'integer', 'min:1', 'max:10'],
            'role' => ['nullable', 'in:member,admin'],
        ]);

        $inv = $this->createInvitation->execute($request->user(), $data);

        $link = route('invite.accept', ['token' => $inv->token]);

        return back()->with('status', "Invitation created: {$link}");
    }

    public function index(): View
    {
        $invs = Invitation::query()->latest()->paginate(20);

        return view('admin.invitations.index', compact('invs'));
    }
}
