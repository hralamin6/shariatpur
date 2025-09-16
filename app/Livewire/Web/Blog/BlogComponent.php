<?php

namespace App\Livewire\Web\Blog;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Upazila;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class BlogComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $blog_category_id = null;

    public ?int $upazila_id = null;

    public string $title = '';

    public string $content = '';

    public string $status = 'active';

    public string $search = '';

    public ?int $filter_category_id = null;

    public ?int $filter_upazila_id = null;

    public $photo; // uploaded file

    public string $image_url = '';

    public ?int $detailsId = null;

    public array $blogDetails = [];

    protected function rules(): array
    {
        return [
            'blog_category_id' => 'required|exists:blog_categories,id',
            'upazila_id' => 'nullable|exists:upazilas,id',
            'title' => 'required|string|max:200|unique:blogs,title,'.($this->selectedId ?? 'NULL').',id',
            'content' => 'required|string',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:4096',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();

        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(Blog $blog): void
    {
        if (! auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($blog->user_id !== auth()->id() && ! $this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createBlog(): void
    {
        if (! auth()->check()) {
            redirect()->route('login')->send();

            return;
        }
        $this->validate();

        $blog = Blog::create([
            'user_id' => auth()->id(),
            'blog_category_id' => $this->blog_category_id,
            'upazila_id' => $this->upazila_id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
        ]);

        if ($this->image_url !== '' && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $blog->addMediaFromUrl($this->image_url)->usingFileName($blog->id.'.'.$extension)->toMediaCollection('blog');
            $path = storage_path('app/public/Blog/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        } elseif ($this->photo) {
            $media = $blog->addMedia($this->photo->getRealPath())->usingFileName(($blog->id.'-'.$blog->title).'.'.$this->photo->extension())->toMediaCollection('blog');
            $path = storage_path('app/public/Blog/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'blog-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectBlogForEdit(int $id): void
    {
        $blog = Blog::findOrFail($id);
        $this->authorizeOwnerOrAdmin($blog);

        $this->selectedId = $blog->id;
        $this->blog_category_id = $blog->blog_category_id;
        $this->upazila_id = $blog->upazila_id;
        $this->title = $blog->title;
        $this->content = $blog->content;
        $this->status = $blog->status ?? 'active';

        $this->dispatch('open-modal', 'blog-form');
    }

    public function updateBlog(): void
    {
        if (! $this->selectedId) {
            return;
        }
        $this->validate();

        $blog = Blog::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($blog);

        $blog->update([
            'blog_category_id' => $this->blog_category_id,
            'upazila_id' => $this->upazila_id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
        ]);

        if ($this->image_url !== '' && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $blog->addMediaFromUrl($this->image_url)->usingFileName($blog->id.'.'.$extension)->toMediaCollection('blog');
            $path = storage_path('app/public/Blog/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        } elseif ($this->photo) {
            $media = $blog->addMedia($this->photo->getRealPath())->usingFileName(($blog->id.'-'.$blog->title).'.'.$this->photo->extension())->toMediaCollection('blog');
            $path = storage_path('app/public/Blog/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'blog-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $blog = Blog::findOrFail($id);
        $this->authorizeOwnerOrAdmin($blog);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-blog');
    }

    public function deleteSelectedBlog(): void
    {
        if (! $this->selectedId) {
            return;
        }
        $blog = Blog::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($blog);
        $blog->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-blog');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function openBlogForm(): void
    {
        $this->resetForm();
        if ($this->filter_category_id) {
            $this->blog_category_id = $this->filter_category_id;
        }
        $this->dispatch('open-modal', 'blog-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'blog_category_id', 'upazila_id', 'title', 'content', 'status', 'photo', 'image_url']);
        $this->status = 'active';
    }

    public function mount($cat_id = null): void
    {
        if ($cat_id) {
            $this->filter_category_id = (int) $cat_id;
            $this->blog_category_id = (int) $cat_id;
        }
    }

    public function render()
    {
        $query = Blog::with('blogCategory', 'upazila', 'user')->where('status', 'active');
        if ($this->filter_category_id) {
            $query->where('blog_category_id', $this->filter_category_id);
        }
        if ($this->filter_upazila_id) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        if (filled($this->search)) {
            $s = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', $s)
                    ->orWhere('content', 'like', $s);
            });
        }
        $blogs = $query->latest()->get();
        $categories = BlogCategory::orderBy('name')->get();
        $upazilas = Upazila::whereBetween('id', [322, 327])->orderBy('name')->get();

        return view('livewire.web.blog.blog-component', compact('blogs', 'categories', 'upazilas'))
            ->layout('components.layouts.web');
    }

    private function buildBlogDetailsPayload(Blog $blog): array
    {
        $photoUrl = null;
        if (method_exists($blog, 'getFirstMediaUrl')) {
            $photoUrl = $blog->getFirstMediaUrl('blog', 'avatar') ?: $blog->getFirstMediaUrl('blog');
        }

        return [
            'title' => $blog->title,
            'category' => $blog->blogCategory?->name,
            'upazila' => $blog->upazila?->name,
            'content' => $blog->content,
            'status' => $blog->status,
            'photo_url' => $photoUrl,
            'created_by' => $blog->user?->name,
            'created_at' => optional($blog->created_at)->format('d M Y'),
        ];
    }

    public function showDetails(int $id): void
    {
        $blog = Blog::with('blogCategory', 'upazila', 'user')->findOrFail($id);
        $this->detailsId = $id;
        $this->blogDetails = $this->buildBlogDetailsPayload($blog);
        $this->dispatch('open-modal', 'blog-details');
    }
}
