<x-filament-widgets::widget>
    <x-filament::card class="bg-white dark:bg-gray-800">
        <h2 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Recent Notifications</h2>
        @forelse($this->getNotifications() as $notification)
            <div class="mb-3 p-2 bg-gray-100 dark:bg-gray-700 rounded">
                <p class="text-gray-800 dark:text-gray-200">{{ $notification->data['message'] }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                <button wire:click="markAsRead('{{ $notification->id }}')" class="btn btn-success text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 ">Mark as read</button>
            </div>
        @empty
            <p class="text-gray-600 dark:text-gray-400">No new notifications.</p>
        @endforelse
    </x-filament::card>
</x-filament-widgets::widget>
