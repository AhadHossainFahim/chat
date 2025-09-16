<x-layouts.app :title="__('Chat')">
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Chat Header -->
        <div class="bg-gray-100 px-6 py-4 border-b">
          <h2 class="text-xl font-semibold">
            Chat for: {{ $chat->ticket->title }}
          </h2>
          <p class="text-sm text-gray-600">
            Between {{ $chat->developer->name }} and {{ $chat->ticket->client->name }}
          </p>
        </div>

        <!-- Messages Area -->
        <livewire:chat-messages :chat="$chat" />

        <!-- Message Form -->
        <livewire:chat-message-form :chat="$chat" />
      </div>
    </div>
  </div>
</x-layouts.app>
