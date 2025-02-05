<div class="pb-12">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-10 shadow-black">
            <x-form wire:submit="store" enctype="multipart/form-data">
                <x-input label="Title" wire:model="title" />
                <x-input label="Slug" wire:model="slug" />
                <x-editor wire:model="content" label="Content" disk="public" :config="$config" hint="The full content description" />
                <div class="grid grid-flow-col gap-4">
                    <x-file wire:model="banner" label="Banner" change-text="Change" accept="image/png, image/jpeg, video/*" />
                    <x-radio label="Status" :options="$options" wire:model="status" class="w-full" />
                </div>

                @if ($banner)
                    <figure class="mt-5">
                        @if (str_contains($banner->getMimeType(), 'video'))
                            <video class="h-40 rounded-lg">
                                <source src="{{ $banner->temporaryUrl() }}">
                            </video>
                        @else
                            <img src="{{ $banner->temporaryUrl() }}" class="h-40 rounded-lg" />
                        @endif
                        <figcaption>{{ $banner->hashName()}}</figcaption>
                    </figure>
                @endif

                <x-slot:actions>
                    <x-button label="Cancel" />
                    <x-button label="Submit" class="btn-primary" type="submit" spinner="save" />
                </x-slot:actions>
            </x-form>
        </div>
    </div>
</div>
