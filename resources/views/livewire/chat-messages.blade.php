<div
  x-data
  x-ref="messagesContainer"
  x-init="$nextTick(() => { $refs.messagesContainer.scrollTop = $refs.messagesContainer.scrollHeight })"
  x-on:scroll-to-bottom.window="$nextTick(() => { $refs.messagesContainer.scrollTop = $refs.messagesContainer.scrollHeight })"
  class="h-96 overflow-y-auto px-6 py-4"
>
  @forelse($messages as $message)
    <div class="mb-4 {{ $message->user_id === auth()->id() ? 'text-right' : 'text-left' }}">
      <div class="inline-block max-w-xs lg:max-w-md">
        <div class="{{ $message->user_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }} rounded-lg px-4 py-2">
          <p class="text-sm font-semibold">{{ $message->user->name }}</p>
          <p>{{ $message->message }}</p>
          <p class="text-xs {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }} mt-1">
            @if ($message->created_at->isToday())
              {{ $message->created_at->format('h:i A') }}
            @else
              {{ $message->created_at->format('M j, Y h:i A') }}
            @endif
          </p>
        </div>
      </div>
    </div>
  @empty
    <p class="text-center text-gray-500">No messages yet. Start the conversation!</p>
  @endforelse
</div>
