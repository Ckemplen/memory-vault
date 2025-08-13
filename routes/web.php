<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\InvitationController as AdminInvitationController;
use App\Http\Controllers\Auth\InviteAcceptController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('home') : view('auth.login');
})->name('landing');

require __DIR__.'/auth.php';

Route::middleware('guest')->group(function (): void {
    Route::get('/invite/{token}', [InviteAcceptController::class, 'show'])->name('invite.accept');
    Route::post('/invite/{token}', [InviteAcceptController::class, 'store'])->name('invite.accept.store');
});

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/home', fn () => view('home'))->name('home');

    Route::get('/admin/invitations', [AdminInvitationController::class, 'index'])
        ->middleware('can:manage-invitations')->name('admin.invitations.index');
    Route::post('/admin/invitations', [AdminInvitationController::class, 'store'])
        ->middleware('can:manage-invitations')->name('admin.invitations.store');
});
