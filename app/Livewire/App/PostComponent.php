<?php

namespace App\Livewire\App;

use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use misterspelik\LaravelPdf\Facades\Pdf;

class PostComponent extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $selectedRows = [];
    public $selectPageRows = false;
    public $itemPerPage = 20;
    public $orderBy = 'id';
    public $searchBy = 'title';
    public $orderDirection = 'asc';
    public $search = '';
    public $itemStatus;
    protected $queryString = [
        'search' => ['except' => ''],
        'itemStatus' => ['except' => null],
    ];

    public $post;
    public $title, $content = 'Sample content', $slug, $status = 'draft', $excerpt, $tags, $meta_title, $meta_description, $published_at;
    public $category_id, $user_id;
    public function pdfGenerate()
    {
        return response()->streamDownload(function () {
            $posts= $this->data;
            $pdf = Pdf::loadView('pdf.words', compact('posts'));
            return $pdf->stream('users.pdf');
        }, 'users.pdf');
    }
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

    public function changeStatus(Post $post)
    {
        $this->authorize('app.posts.edit');

        $post->status == 'draft' ? $post->update(['status' => 'published']) : $post->update(['status' => 'draft']);

        $this->alert('success', __('Data updated successfully'));
    }

    public function resetData()
    {
        $this->reset('title', 'content', 'slug', 'status', 'excerpt', 'tags', 'meta_title', 'meta_description', 'published_at', 'category_id', 'user_id');
    }

    public function saveData()
    {
        $this->authorize('app.posts.create');
        $this->user_id = Auth::id();
        $this->tags = json_encode(array_map('trim', explode(',', $this->tags))); // Convert to JSON array

        $data = $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'required|string|alpha_dash|unique:posts,slug,',
            'status' => 'required|in:draft,published',
            'excerpt' => 'nullable|string',
            'tags' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $data = Post::create($data);
        $var = $data->id;
        $this->dispatch('dataAdded', dataId: "item-id-$var");
        $this->goToPage($this->getDataProperty()->lastPage());
        $this->alert('success', __('Data saved successfully!'));
        $this->resetData();
    }

    public function loadData(Post $post)
    {
        $this->resetData();
        $this->title = $post->title;
        $this->content = $post->content;
        $this->slug = $post->slug;
        $this->status = $post->status;
        $this->excerpt = $post->excerpt;
        $this->tags = $post->tags;
        $this->meta_title = $post->meta_title;
        $this->meta_description = $post->meta_description;
        $this->published_at = $post->published_at;
        $this->category_id = $post->category_id;
        $this->user_id = $post->user_id;
        $this->post = $post;
    }

    public function editData()
    {
        $this->authorize('app.posts.edit');
        $this->tags = json_encode(array_map('trim', explode(',', $this->tags))); // Convert to JSON array

        $data = $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'slug' => 'required|string|alpha_dash|unique:posts,slug,' . $this->post->id,
            'status' => 'required|in:draft,published',
            'excerpt' => 'nullable|string',
            'tags' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $this->post->update($data);
        $var = $this->post->id;
        $this->dispatch('dataAdded', dataId: "item-id-$var");
        $this->alert('success', __('Data updated successfully'));
        $this->resetData();
    }

    public function updatedTitle()
    {
        $this->slug = \Str::slug($this->title);
    }

    public function getDataProperty()
    {
        return Post::where($this->searchBy, 'like', '%' . $this->search . '%')
            ->orderBy($this->orderBy, $this->orderDirection)
            ->when($this->itemStatus, function ($query) {
                return $query->where('status', $this->itemStatus);
            })
            ->paginate($this->itemPerPage)->withQueryString();
    }

    public function deleteMultiple()
    {
        $this->authorize('app.posts.delete');

        $posts = Post::whereIn('id', $this->selectedRows)->get();

        foreach ($posts as $post) {
            $post->delete();  // Delete each model individually
        }

        $this->selectPageRows = false;
        $this->selectedRows = [];
        $this->alert('success', __('Data deleted successfully'));
    }

    public function deleteSingle(Post $post)
    {
        $this->authorize('app.posts.delete');
        $post->delete();
        $this->alert('success', __('Data deleted successfully'));
    }

    public function orderByDirection($field)
    {
        if ($this->orderBy == $field) {
            $this->orderDirection === 'asc' ? $this->orderDirection = 'desc' : $this->orderDirection = 'asc';
        } else {
            $this->orderBy = $field;
            $this->orderDirection = 'asc';
        }
    }

    public function render()
    {
        $this->authorize('app.posts.index');
        $items = $this->data;
        $categories = \App\Models\Category::whereNotNull('parent_id')->get();

        return view('livewire.app.post-component', compact('items', 'categories'));
    }
}
