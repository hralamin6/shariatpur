<?php

namespace App\Livewire\Web\News;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Upazila;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewsComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $news_category_id = null;
    public ?int $upazila_id = null;
    public string $title = '';
    public string $content = '';
    public string $status = 'active';
    public bool $is_pinned = false;

    public string $search = '';
    public ?int $filter_category_id = null;
    public ?int $filter_upazila_id = null;

    public $photo;
    public string $image_url = '';

    protected function rules(): array
    {
        return [
            'news_category_id' => 'required|exists:news_categories,id',
            'upazila_id' => 'nullable|exists:upazilas,id',
            'title' => 'required|string|max:200|unique:news,title,' . ($this->selectedId ?? 'NULL') . ',id',
            'content' => 'required|string',
            'status' => 'required|in:active,inactive',
            'is_pinned' => 'boolean',
            'photo' => 'nullable|image|max:4096',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(News $news): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($news->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createNews(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $news = News::create([
            'user_id' => auth()->id(),
            'news_category_id' => $this->news_category_id,
            'upazila_id' => $this->upazila_id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'is_pinned' => (bool) $this->is_pinned,
        ]);

        if ($this->image_url !== '' && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $news->addMediaFromUrl($this->image_url)->usingFileName($news->id . '.' . $extension)->toMediaCollection('news');
            $path = storage_path('app/public/News/'.$media->id.'/'. $media->file_name);
            if (file_exists($path)) { @unlink($path); }
        } elseif ($this->photo) {
            $media = $news->addMedia($this->photo->getRealPath())->usingFileName(($news->id.'-'.$news->title).'.'.$this->photo->extension())->toMediaCollection('news');
            $path = storage_path('app/public/News/'.$media->id.'/'. $media->file_name);
            if (file_exists($path)) { @unlink($path); }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'news-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectNewsForEdit(int $id): void
    {
        $news = News::findOrFail($id);
        $this->authorizeOwnerOrAdmin($news);

        $this->selectedId = $news->id;
        $this->news_category_id = $news->news_category_id;
        $this->upazila_id = $news->upazila_id;
        $this->title = $news->title;
        $this->content = $news->content;
        $this->status = $news->status ?? 'active';
        $this->is_pinned = (bool) ($news->is_pinned ?? false);

        $this->dispatch('open-modal', 'news-form');
    }

    public function updateNews(): void
    {
        if (!$this->selectedId) { return; }
        $this->validate();

        $news = News::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($news);

        $news->update([
            'news_category_id' => $this->news_category_id,
            'upazila_id' => $this->upazila_id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'is_pinned' => (bool) $this->is_pinned,
        ]);

        if ($this->image_url !== '' && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $news->addMediaFromUrl($this->image_url)->usingFileName($news->id . '.' . $extension)->toMediaCollection('news');
            $path = storage_path('app/public/News/'.$media->id.'/'. $media->file_name);
            if (file_exists($path)) { @unlink($path); }
        } elseif ($this->photo) {
            $media = $news->addMedia($this->photo->getRealPath())->usingFileName(($news->id.'-'.$news->title).'.'.$this->photo->extension())->toMediaCollection('news');
            $path = storage_path('app/public/News/'.$media->id.'/'. $media->file_name);
            if (file_exists($path)) { @unlink($path); }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'news-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $news = News::findOrFail($id);
        $this->authorizeOwnerOrAdmin($news);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-news');
    }

    public function deleteSelectedNews(): void
    {
        if (!$this->selectedId) return;
        $news = News::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($news);
        $news->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-news');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function openNewsForm(): void
    {
        $this->resetForm();
        if ($this->filter_category_id) {
            $this->news_category_id = $this->filter_category_id;
        }
        $this->dispatch('open-modal', 'news-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId','news_category_id','upazila_id','title','content','status','is_pinned','photo','image_url']);
        $this->status = 'active';
        $this->is_pinned = false;
    }

    public function mount($cat_id = null): void
    {
        if ($cat_id) {
            $this->filter_category_id = (int) $cat_id;
            $this->news_category_id = (int) $cat_id;
        }
    }

    public function render()
    {
        $query = News::with('category','upazila','user')->where('status','active');
        if ($this->filter_category_id) {
            $query->where('news_category_id', $this->filter_category_id);
        }
        if ($this->filter_upazila_id) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function($q) use($s) {
                $q->where('title','like',$s)
                  ->orWhere('content','like',$s);
            });
        }
        $newsList = $query->latest()->get();
        $categories = NewsCategory::orderBy('name')->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.news.news-component', compact('newsList','categories','upazilas'))
            ->layout('components.layouts.web');
    }
}

