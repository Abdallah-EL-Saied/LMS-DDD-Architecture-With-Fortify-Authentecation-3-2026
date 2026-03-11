<?php

use App\Application\Identity\Services\DeleteUserAction;
use App\Domains\Identity\RepositoryInterface\IUserRepository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Component;

new #[Layout('layouts.app')] #[Title('User Management')] class extends Component {
    /**
     * Delete a user.
     */
    public function deleteUser(int $userId, DeleteUserAction $deleteUserAction): void
    {
        $deleteUserAction->execute($userId);

        $this->dispatch('user-deleted');
    }

    /**
     * Get users for the list.
     */
    #[Computed]
    public function users()
    {
        return app(IUserRepository::class)->all();
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <flux:heading size="xl" class="mb-6">{{ __('Users Management') }}</flux:heading>

            <div class="space-y-4">
                @foreach ($this->users as $user)
                    <div class="flex items-center justify-between p-4 border rounded-lg dark:border-zinc-800">
                        <div>
                            <flux:text class="font-bold">{{ $user->name() }}</flux:text>
                            <div class="flex gap-2 items-center">
                                <flux:text variant="subtle">{{ $user->email() }}</flux:text>
                                @foreach($user->roles() as $role)
                                    <flux:badge size="sm" variant="neutral">{{ $role }}</flux:badge>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex gap-2">
                            {{-- Edit button could go here --}}

                            <flux:button variant="danger" size="sm" wire:click="deleteUser({{ $user->id() }})"
                                wire:confirm="Are you sure you want to delete this user?">
                                {{ __('Delete') }}
                            </flux:button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $this->users->links() }}
            </div>
        </div>
    </div>
</div>