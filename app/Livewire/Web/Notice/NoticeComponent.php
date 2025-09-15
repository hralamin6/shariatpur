<?php

namespace App\Livewire\Web\Notice;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class NoticeComponent extends Component
{
    use LivewireAlert;
    use AuthorizesRequests;
    use WithFileUploads;

    public ?int $selectedId = null;

    public string $search = '';
    public ?bool $filter_pinned = null; // true,false,null

    public string $title = '';
    public string $content = '';
    public bool $pinned = false;

    public $photo;
    public string $image_url = '';

    public ?int $detailsId = null;
    public array $noticeDetails = [];

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'content' => 'nullable|string|max:5000',
            'pinned' => 'boolean',
            'photo' => 'nullable|image|max:4096',
            'image_url' => 'nullable|url|max:1000',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(Notice $notice): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($notice->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function resetForm(): void
    {
        $this->reset(['title', 'content', 'pinned', 'photo', 'image_url', 'selectedId'
        ]);
        $this->pinned = false;
    }

    public function openNoticeForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'notice-form');
    }

    public function createNotice(): void
    {
        $this->authorize('app.notices.create');
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $notice = Notice::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'content' => $this->content ?: null,
            'pinned' => (bool) $this->pinned,
        ]);

        // Media handling
        if (!empty($this->image_url) && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $notice->addMediaFromUrl($this->image_url)->usingFileName($notice->id . '.' . $extension)->toMediaCollection('notice');
            $path = storage_path('app/public/Notice/' . $media->id . '/' . $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo) {
            $media = $notice->addMedia($this->photo->getRealPath())->usingFileName(($notice->title ?: 'notice') . '.' . $this->photo->extension())->toMediaCollection('notice');
            $path = storage_path('app/public/Notice/' . $media->id . '/' . $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'notice-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectNoticeForEdit(int $id): void
    {
        $notice = Notice::findOrFail($id);
        $this->authorizeOwnerOrAdmin($notice);

        $this->selectedId = $notice->id;
        $this->title = $notice->title;
        $this->content = $notice->content ?? '';
        $this->pinned = (bool) ($notice->pinned ?? false);
        $this->dispatch('open-modal', 'notice-form');
    }

    public function updateNotice(): void
    {
        if (!$this->selectedId) {
            return;
        }
        $this->authorize('app.notices.edit');
        $this->validate();

        $notice = Notice::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($notice);

        $notice->update([
            'title' => $this->title,
            'content' => $this->content ?: null,
            'pinned' => (bool) $this->pinned,
        ]);

        if (!empty($this->image_url) && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $notice->addMediaFromUrl($this->image_url)->usingFileName($notice->id . '.' . $extension)->toMediaCollection('notice');
            $path = storage_path('app/public/Notice/' . $media->id . '/' . $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo) {
            $media = $notice->addMedia($this->photo->getRealPath())->usingFileName(($notice->title ?: 'notice') . '.' . $this->photo->extension())->toMediaCollection('notice');
            $path = storage_path('app/public/Notice/' . $media->id . '/' . $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'notice-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $notice = Notice::findOrFail($id);
        $this->authorizeOwnerOrAdmin($notice);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-notice');
    }

    public function deleteSelectedNotice(): void
    {
        $this->authorize('app.notices.delete');
        if (!$this->selectedId) {
            return;
        }
        $notice = Notice::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($notice);
        $notice->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-notice');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function showDetails(int $id): void
    {
        $notice = Notice::with('user')->findOrFail($id);
        $photoUrl = method_exists($notice, 'getFirstMediaUrl') ? ($notice->getFirstMediaUrl('notice','avatar') ?: $notice->getFirstMediaUrl('notice')) : null;
        $this->detailsId = $id;
        $this->noticeDetails = [
            'id' => $notice->id,
            'title' => $notice->title,
            'content' => $notice->content,
            'pinned' => (bool) ($notice->pinned ?? false),
            'photo_url' => $photoUrl,
            'created_by' => $notice->user?->name,
            'created_at' => optional($notice->created_at)->format('d M Y, h:i A'),
        ];
        $this->dispatch('open-modal', 'notice-details');
    }

    public function render()
    {
        $query = Notice::query()->with('user');

        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function($q) use($s) {
                $q->where('title','like',$s)
                  ->orWhere('content','like',$s);
            });
        }

        if ($this->filter_pinned !== null) {
            $query->where('pinned', (bool) $this->filter_pinned);
        }

        $notices = $query->orderByDesc('pinned')
            ->orderByDesc('created_at')
            ->latest()
            ->get();

        return view('livewire.web.notice.notice-component', compact('notices'))
            ->layout('components.layouts.web');
    }
}
