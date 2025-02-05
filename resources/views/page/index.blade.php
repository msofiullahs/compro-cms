
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="grid justify-items-end">
                    <x-button link="/pages/form" wire:navigate class="self-end">Create New Page</x-button>
                </div>
                @livewire('page-table')
            </div>
        </div>
    </div>
</x-app-layout>

