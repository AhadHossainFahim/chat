<div class="px-6 py-4 border-t">
  <form wire:submit="sendMessage" class="flex gap-2">
    <input type="text" wire:model="message" placeholder="Type your message..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
      Send
    </button>
  </form>
  @error('message')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
  @enderror
</div>
