<div>
    <div class="max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-10 shadow-black">
            <x-header title="Media" separator>
                <x-slot:middle class="!justify-end">
                    <x-select label="Type" :options="$options" wire:model.live="fileType" inline />
                </x-slot:middle>>
                <x-slot:actions>
                    <x-button @click="$wire.fileModal = true">Upload</x-button>
                </x-slot:actions>
            </x-header>
            @foreach ($medias as $media)
                {{-- <x-list-item :item="$media" value="filename" sub-value="filetype" avatar="file_url ? file_url : " /> --}}
                <x-list-item :item="$media" no-separator no-hover>
                    <x-slot:avatar>
                        @if (str_contains($media->filetype, 'image'))
                            <x-avatar :image="$media->file_url" class="!w-14 !rounded-lg" />
                        @elseif (str_contains($media->filetype, 'video'))
                            <x-icon name="o-video-camera" class="w-14 h-14 bg-orange-500 text-white p-2 !rounded-lg" />
                        @else
                            <x-icon name="o-puzzle-piece" class="w-14 h-14 bg-orange-500 text-white p-2 !rounded-lg" />
                        @endif
                    </x-slot:avatar>
                    <x-slot:value>
                        {{$media->filename}}
                    </x-slot:value>
                    @if (!empty($media->filetype) && !empty($media->file_size))
                        <x-slot:sub-value>
                            {{$media->filetype}} | {{ $media->file_size }}
                        </x-slot:sub-value>
                    @endif
                    <x-slot:actions>
                        <x-button icon="o-trash" class="text-red-500" wire:click="delete({{ $media->id }})" spinner />
                    </x-slot:actions>
                </x-list-item>
            @endforeach

            <x-pagination :rows="$medias" />
        </div>
    </div>
    <x-modal wire:model="fileModal" title="Upload Media" class="backdrop-blur">
        <x-form wire:submit="store" enctype="multipart/form-data">
            <div class="mb-5">
                    <x-file wire:model="myfile" change-text="Change" accept="image/png, image/jpeg, video/*" />
                    @if ($myfile)
                        <figure class="mt-5 grid justify-items-center">
                            @if (str_contains($myfile->getMimeType(), 'video'))
                                <video class="h-40 rounded-lg">
                                    <source src="{{ $myfile->temporaryUrl() }}">
                                </video>
                            @else
                                <img src="{{ $myfile->temporaryUrl() }}" class="h-40 rounded-lg" />
                            @endif
                            <figcaption>{{ $myfile->hashName()}}</figcaption>
                        </figure>
                    @endif
            </div>
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.fileModal = false" />
                <x-button label="Upload" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
