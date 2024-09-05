<?php

namespace App\Livewire\App;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CategoryComponent extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $selectedRows = [];
    public $selectPageRows = false;
    public $itemPerPage = 20;
    public $orderBy = 'id';
    public $searchBy = 'name';
    public $orderDirection = 'asc';
    public $search = '';
    public $itemStatus;
    protected $queryString = [
        'search' => ['except' => ''],
        'itemStatus' => ['except' => null],
    ];

    public $category;
    public $c;
    public $name, $description = '', $parent_id, $status = 'draft';

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
                return (string)$id;
            });
        } else {
            $this->reset('selectedRows', 'selectPageRows');
        }
    }

    public function resetData()
    {
        $this->reset('name', 'description', 'parent_id', 'status');
    }

    public function saveData()
    {
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:published,draft',
        ]);

         $data = Category::create($data);
        $var = $data->id;
        $this->dispatch('dataAdded', dataId: "item-id-$var");
        $this->goToPage($this->getDataProperty()->lastPage());
        $this->alert('success', __('Data saved successfully!'));
        $this->resetData();
    }

    public function loadData(Category $category)
    {
        $this->resetData();
        $this->name = $category->name;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
        $this->status = $category->status;
        $this->category = $category;
        $this->c = $category;
    }

    public function editData()
    {
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:published,draft'
        ]);

        $this->category->update($data);
        $var = $this->category->id;
        $this->dispatch('dataAdded', dataId: "item-id-$var");
        $this->alert('success', __('Data updated successfully!'));
        $this->resetData();
    }

    public function deleteSingle(Category $category)
    {
        $category->delete();
        $this->alert('success', __('Data deleted successfully!'));
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
    public function deleteMultiple()
    {
        Category::whereIn('id', $this->selectedRows)->delete();
        $this->selectedRows = [];
        $this->alert('success', __('Data deleted successfully!'));
    }

    public function getDataProperty()
    {
        return Category::where($this->searchBy, 'like', '%' . $this->search . '%')
            ->orderBy($this->orderBy, $this->orderDirection)->when($this->itemStatus, function ($query) {
                return $query->where('status', $this->itemStatus);
            })
            ->paginate($this->itemPerPage)->withQueryString();
    }
    public function changeStatus(Category $category)
    {
        $this->authorize('app.categories.edit');

        $category->status=='draft'?$category->update(['status'=>'published']):$category->update(['status'=>'draft']);
//        $page->notify(new PageApproved($page->name, $page->status, $page));

        $this->alert('success', __('Data updated successfully'));
    }
    public function render()
    {
        $categories = $this->data;
        $selectCategories = Category::whereNull('parent_id')->when($this->category, function ($query) {
            return $query->where('id', '!=', $this->category->id);
        })->get();
        return view('livewire.app.category-component', compact('categories', 'selectCategories'));
    }
}
