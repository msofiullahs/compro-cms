<?php

namespace App\Livewire\Pages;

use App\Models\Media;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Create extends Component
{
    use WithFileUploads;

    #[Validate('required')]
    public $title = '';

    #[Validate('unique:pages')]
    public $slug = '';

    #[Validate('required')]
    public $content = '';

    #[Validate('max:2048')]
    public $banner;

    #[Validate('required')]
    public $status = '';

    public function render()
    {
        $config = [
            // 'theme'=> 'silver',
            'skin'      => 'oxide-dark',
            'mode'      => 'classic',
            'menubar'   => 'edit insert view format table tools help',
            'toolbar'   => 'undo redo | styles | bold italic | link image table',
            'min_height'=> 500,
            'images_upload_url' => route('api.media.upload'),
        ];
        $options = [
            ['id' => 'draft', 'name' => 'Draft'],
            ['id' => 'publish', 'name' => 'Publish'],
        ];
        return view('livewire.pages.create')->with([
            'config'    => $config,
            'options'   => $options
        ]);
    }

    public function store()
    {
        $this->validate();

        $user = Auth::user();
        try {
            $mediaId = null;
            if (!empty($this->banner)) {
                $this->banner->storePubliclyAs(path: 'photos', name: $this->banner->hashName());

                $media = new Media();
                $media->filename = $this->banner->hashName();
                $media->filetype = $this->banner->getMimeType();
                $media->uploaded_by = $user->id;
                $media->save();

                $mediaId = $media->id;
            }

            $slug = $this->slug;
            if (empty($slug)) {
                $slug = Str::of($this->title)->slug('-');
            }
            $counter = Page::where('slug', 'LIKE', $slug.'%')->count();
            if ($counter > 0) {
                $slug = $slug.'-'.$counter;
            }

            $page = new Page();
            $page->title = $this->title;
            $page->slug = $slug;
            $page->content = $this->content;
            $page->banner = $mediaId;
            $page->author = $user->id;
            $page->status = $this->status;
            $page->save();

            session()->flash('success', 'Page has successfully created');

            return redirect()->route('page.index');
        } catch (\Exception $e) {
            session()->flash('error', 'There is something error while creating page');
            return redirect()->back();
        }
    }
}
