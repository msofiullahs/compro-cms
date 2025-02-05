<?php

namespace App\Livewire\Medias;

use App\Models\Media;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithFileUploads;
    public $fileType = '';

    public bool $fileModal = false;

    #[Validate('required|max:2048')]
    public $myfile;

    public function render()
    {
        $medias = Media::latest();

        if (!empty($this->fileType)) {
            $medias = $medias->where('filetype', 'LIKE', $this->fileType.'%');
        }

        $medias = $medias->paginate(10);

        $options = [
            ['id' => '', 'name' => 'All'],
            ['id' => 'image', 'name' => 'Image'],
            ['id' => 'video', 'name' => 'Video'],
        ];

        return view('livewire.medias.index')->with([
            'medias'    => $medias,
            'options'   => $options,
        ]);
    }

    public function store()
    {
        $this->validate();

        $user = Auth::user();
        try {
            $this->myfile->storePubliclyAs(path: 'photos', name: $this->myfile->hashName());

            $media = new Media();
            $media->filename = $this->myfile->hashName();
            $media->filetype = $this->myfile->getMimeType();
            $media->uploaded_by = $user->id;
            $media->save();

            session()->flash('success', 'Media has successfully uploaded');

            return redirect()->route('media.index');
        } catch (\Exception $e) {
            session()->flash('error', 'There is something error while uploading media');
            return redirect()->route('media.index');
        }
    }

    public function delete($id)
    {
        try {
            $checkOnPage = Page::where('banner', $id)->exists();

            if (!$checkOnPage) {
                $media = Media::find($id);
                Storage::delete('photos/'.$media->filename);
                $media->delete();

                session()->flash('success', 'Media has successfully deleted');
            } else {
                session()->flash('error', 'Media is not deleted, this media is attached to a page!');
            }
            return redirect()->route('media.index');
        } catch (\Exception $e) {
            session()->flash('error', 'There is something error while uploading media');
            return redirect()->route('media.index');
        }
    }
}
