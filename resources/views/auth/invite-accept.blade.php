<x-guest-layout>
  <div class="mb-4 text-sm">
    You were invited by {{ $inv->inviter->name }}{{ $inv->email ? " ({$inv->email})" : '' }}.
    {{ $inv->expires_at ? 'This invite expires '.$inv->expires_at->diffForHumans().'.' : '' }}
  </div>

  <form method="POST" action="{{ route('invite.accept.store', $inv->token) }}">
    @csrf
    <div>
      <x-input-label for="name" value="Name" />
      <x-text-input id="name" name="name" class="block mt-1 w-full" required autofocus />
    </div>
    <div class="mt-4">
      <x-input-label for="email" value="Email" />
      <x-text-input id="email" type="email" name="email" class="block mt-1 w-full" value="{{ old('email', $inv->email) }}" {{ $inv->email ? 'readonly' : '' }} required />
    </div>
    <div class="mt-4">
      <x-input-label for="password" value="Password" />
      <x-text-input id="password" type="password" name="password" class="block mt-1 w-full" required autocomplete="new-password" />
    </div>
    <div class="mt-4">
      <x-input-label for="password_confirmation" value="Confirm Password" />
      <x-text-input id="password_confirmation" type="password" name="password_confirmation" class="block mt-1 w-full" required />
    </div>
    <div class="mt-6 flex items-center justify-end">
      <x-primary-button>Create account</x-primary-button>
    </div>
  </form>
</x-guest-layout>
