<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">Memory Vault</h2></x-slot>
  <div class="py-6">Welcome, {{ auth()->user()->name }}.</div>
</x-app-layout>
