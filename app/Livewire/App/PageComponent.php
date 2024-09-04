<?php

namespace App\Livewire\App;

use App\Models\Conversation;
use App\Models\Page;
use App\Models\Role;
use App\Models\User;
use App\Notifications\DeleteNotification;
use App\Notifications\UserApproved;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use misterspelik\LaravelPdf\Facades\Pdf;

class PageComponent extends Component
{

    use WithPagination;
    use LivewireAlert;
    public $selectedRows = [];
    public $selectPageRows = false;
    public $itemPerPage=200;
    public $orderBy = 'id';
    public $searchBy = 'title';
    public $orderDirection = 'asc';
    public $search = '';
    public $itemStatus;
    protected $queryString = [
        'search' => ['except' => ''],
        'itemStatus' => ['except' => null],
    ];

    public $page;
    public $title, $content='asdf', $slug, $status='draft';
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedItemPerPage()
    {
        $this->resetPage();
    }
    public function updatedSelectPageRows($value)
    {
        if ($value) {
            $this->selectedRows = $this->data->pluck('id')->map(function ($id) {
                return (string) $id;
            });
        } else {
            $this->reset('selectedRows', 'selectPageRows');
        }
    }
    public function changeStatus(Page $page)
    {
        $this->authorize('app.pages.edit');

        $page->status=='draft'?$page->update(['status'=>'published']):$page->update(['status'=>'draft']);
//        $page->notify(new PageApproved($page->name, $page->status, $page));

        $this->alert('success', __('Data updated successfully'));
    }
    public function resetData()
    {
        $this->reset('title', 'content', 'slug', 'status');
    }

    public function saveData()
    {
        $this->authorize('app.pages.create');

        $data = $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'required|string|alpha_dash|unique:pages,slug,',
            'status' => 'required|in:published,draft', // Assuming 'published' and 'draft' are valid statuses
        ]);
        $data = Page::create($data);
        $var = $data->id;
        $this->dispatch('dataAdded', dataId: "item-id-$var");
        $this->goToPage($this->getDataProperty()->lastPage());
        $this->alert('success', __('Data updated successfully'));
        $this->resetData();

    }
    public function loadData(Page $page)
    {
        $this->resetData();
        $this->title = $page->title;
        $this->content = $page->content;
        $this->slug = $page->slug;
        $this->status = $page->status;
        $this->page = $page;
    }
    public function editData()
    {
        $this->authorize('app.pages.edit');
        $data = $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'slug' => 'required|string|alpha_dash|unique:pages,slug,' . $this->page->id,
            'status' => 'required|in:published,draft'
        ]);
       $this->page->update($data);
        $var = $this->page->id;
        $this->dispatch('dataAdded', dataId: "item-id-$var");
        $this->alert('success', __('Data updated successfully'));
        $this->resetData();
    }

    public function updatedTitle()
    {
//            dd('ads');
        $this->slug = \Str::slug($this->title);
    }
    public function getDataProperty()
    {
        return Page::where($this->searchBy, 'like', '%'.$this->search.'%')
            ->orderBy($this->orderBy, $this->orderDirection)
            ->when($this->itemStatus, function ($query) {
                return $query->where('status', $this->itemStatus);
            })
            ->paginate($this->itemPerPage)->withQueryString();
    }
//    public function generate_pdf()
//    {
//        return response()->streamDownload(function () {
//            $items= $this->data;
//            $pdf = Pdf::loadView('pdf.pages', compact('items'));
//            return $pdf->stream('pages.pdf');
//        }, 'pages.pdf');
//    }
    public function deleteMultiple()
    {
        $this->authorize('app.pages.delete');

        Page::whereIn('id', $this->selectedRows)->get();
        $pages = Page::whereIn('id', $this->selectedRows)->get();  // Retrieve models

        foreach ($pages as $page) {
            $page->delete();  // Delete each model individually, triggering the deleting event
        }

        $this->selectPageRows = false;
        $this->selectedRows = [];
        $this->alert('success', __('Data deleted successfully'));
    }
    public function deleteSingle(Page $page)
    {
        $this->authorize('app.pages.delete');
        $page->delete();
        $this->alert('success', __('Data deleted successfully'));
    }
    public function orderByDirection($field)
    {
        if ($this->orderBy == $field){

            $this->orderDirection==='asc'? $this->orderDirection='desc': $this->orderDirection='asc';
        }else{
            $this->orderBy = $field;
            $this->orderDirection==='asc';

        }
    }
    public function pdfGenerate()
    {
        return response()->streamDownload(function () {
            $items= $this->data;
            $pdf = Pdf::loadView('pdf.pages', compact('items'));
            return $pdf->stream('pages.pdf');
        }, 'pages.pdf');
    }

    public function render()
    {
        $this->authorize('app.pages.index');
        $items = $this->data;

        return view('livewire.app.page-component', compact('items'));
    }
}
