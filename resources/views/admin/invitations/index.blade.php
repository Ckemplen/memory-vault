@php(use \Illuminate\Support\Str;)
<x-app-layout>
  <x-slot name="header"><h2>Invitations</h2></x-slot>

  <form method="POST" action="{{ route('admin.invitations.store') }}" class="mb-6">
    @csrf
    <div class="grid gap-4 grid-cols-1 md:grid-cols-4">
      <div>
        <x-input-label for="email" value="Email (optional)" />
        <x-text-input id="email" name="email" type="email" class="w-full" />
      </div>
      <div>
        <x-input-label for="expires_in" value="Expires in (days)" />
        <x-text-input id="expires_in" name="expires_in" type="number" min="1" max="365" class="w-full" />
      </div>
      <div>
        <x-input-label for="max_uses" value="Max uses" />
        <x-text-input id="max_uses" name="max_uses" type="number" min="1" max="10" value="1" class="w-full" />
      </div>
      <div class="flex items-end"><x-primary-button>Create invite</x-primary-button></div>
    </div>
  </form>

  @if (session('status')) <div class="p-3 bg-green-100 rounded">{{ session('status') }}</div> @endif

  <table class="min-w-full text-sm mt-4">
    <thead><tr><th>Token</th><th>Email</th><th>Uses</th><th>Expires</th><th>Link</th></tr></thead>
    <tbody>
      @foreach($invs as $inv)
        <tr>
          <td class="font-mono">{{ Str::limit($inv->token,12) }}</td>
          <td>{{ $inv->email ?? '—' }}</td>
          <td>{{ $inv->uses }}/{{ $inv->max_uses }}</td>
          <td>{{ $inv->expires_at?->toDateString() ?? '—' }}</td>
          <td><a class="text-blue-600 underline" href="{{ route('invite.accept', $inv->token) }}" target="_blank">Open</a></td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $invs->links() }}
</x-app-layout>
