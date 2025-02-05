<div class="pb-12">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-10 shadow-black">
            <x-header title="Pages" separator>
                <x-slot:middle class="!justify-end">
                    <x-input icon="o-bolt" placeholder="Search page title" wire:model.live="search" />
                </x-slot:middle>>
                <x-slot:actions>
                    <x-button link="/pages/create" wire:navigate>Create New Page</x-button>
                </x-slot:actions>
            </x-header>
            <x-table :headers="$headers" :rows="$pages" striped with-pagination>
                @scope('actions', $page)
                    <x-button icon="o-pencil" link="{{ route('page.edit', $page->id) }}" wire:navigate class="btn-xs bg-transparent border-none" />
                    <x-button icon="o-trash" wire:click="delete({{ $page->id }})" spinner class="btn-xs bg-transparent border-none" />
                @endscope
            </x-table>
        </div>
    </div>
</div>

