<?php

namespace App\Livewire\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $search = '';

    public function render()
    {
        $pages = Page::latest();

        if (!empty($this->search)) {
            $pages = $pages->where('title', 'LIKE', '%'.$this->search.'%');
        }

        $pages = $pages->paginate(10);

        $headers = [
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'slug', 'label' => 'Slug'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'updated_at', 'label' => 'Last Modified', 'format' => ['date', 'd/m/Y H:i:s']],
        ];

        return view('livewire.pages.index')->with([
            'pages'     => $pages,
            'headers'   => $headers
        ]);
    }

    public function delete($id)
    {
        try {
            $page = Page::find($id);
            $page->delete();

            session()->flash('success', 'Page has successfully deleted');
        } catch (\Exception $e) {
            session()->flash('error', 'There is something error while deleting page');
        }
        return redirect()->route('page.index');
    }
}
