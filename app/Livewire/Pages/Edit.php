<?php

namespace App\Livewire\Pages;

use App\Models\Media;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Edit extends Component
{
    use WithFileUploads;

    public $pageID;

    #[Validate('required')]
    public $title = '';

    public $slug = '';

    #[Validate('required')]
    public $content = '';

    #[Validate('max:2048')]
    public $banner;

    #[Validate('required')]
    public $status = '';

    public $fileType = '';

    public $fileUrl = '';

    public $fileName = '';

    public function mount($id)
    {
        $page = Page::find($id);
        $fileType = null;
        $fileUrl = null;
        if (!empty($page->banner)) {
            $media = Media::find($page->banner);
            $fileName = $media->filename;
            $fileUrl = Storage::url('photos/'.$media->filename);
            $fileType = Str::contains($media->filetype, 'video') ? 'video' : 'image';
        }
        $this->pageID = $page->id;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->content = $page->content;
        $this->status = $page->status;
        $this->fileType = $fileType;
        $this->fileUrl = $fileUrl;
        $this->fileName = $fileName;
    }

    public function render()
    {;
        $config = [
            // 'theme'=> 'silver',
            'skin'      => 'oxide-dark',
            'plugins'   => 'autoresize',
        ];
        $options = [
            ['id' => 'draft', 'name' => 'Draft'],
            ['id' => 'publish', 'name' => 'Publish'],
        ];
        return view('livewire.pages.edit')->with([
            'config'    => $config,
            'options'   => $options
        ]);
    }

    public function updateBanner()
    {
        $this->fileName = false;
    }

    public function update($id)
    {
        $this->validate();

        $user = Auth::user();
        $page = Page::find($id);
        try {
            if (!empty($this->banner)) {
                $this->banner->storePubliclyAs(path: 'photos', name: $this->banner->hashName());

                $media = new Media();
                $media->filename = $this->banner->hashName();
                $media->filetype = $this->banner->getMimeType();
                $media->uploaded_by = $user->id;
                $media->save();

                $mediaId = $media->id;

                $page->banner = $mediaId;
            }

            $slug = $this->slug;
            if ($this->slug != $page->slug) {
                if (empty($slug)) {
                    $slug = Str::of($this->title)->slug('-');
                }
                $counter = Page::where('slug', 'LIKE', $slug.'%')->count();
                if ($counter > 0) {
                    $slug = $slug.'-'.$counter;
                }
            }

            $page->title = $this->title;
            $page->slug = $slug;
            $page->content = $this->content;
            $page->last_updated_by = $user->id;
            $page->status = $this->status;
            $page->save();

            session()->flash('success', 'Page has successfully updated');

            return redirect()->route('page.index');
        } catch (\Exception $e) {
            session()->flash('error', 'There is something error while updating page');
            return redirect()->back();
        }
    }
}
