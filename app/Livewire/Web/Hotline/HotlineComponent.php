<?php

namespace App\Livewire\Web\Hotline;

use App\Models\Hotline;
use Livewire\Component;
use Livewire\WithFileUploads;

class HotlineComponent extends Component
{
    use WithFileUploads;

    public ?int $selectedId = null;

    public string $title = '';
    public string $phone = '';
    public string $link = '';
    public string $status = 'active';

    public string $search = '';

    public $photo; // uploaded file
    public string $image_url = '';

    public ?int $detailsId = null;
    public array $hotlineDetails = [];

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:150|unique:hotlines,title,' . ($this->selectedId ?? 'NULL') . ',id',
            'phone' => 'nullable|string|max:30',
            'link' => 'nullable|url|max:500',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(Hotline $hotline): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($hotline->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createHotline(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $hotline = Hotline::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'phone' => $this->phone ?: null,
            'link' => $this->link ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $hotline->addMediaFromUrl($this->image_url)->usingFileName($hotline->id. '.' . $extension)->toMediaCollection('hotline');
            $path = storage_path("app/public/Hotline/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo!=null) {
            $media = $hotline->addMedia($this->photo->getRealPath())->usingFileName($hotline->id. '.' . $this->photo->extension())->toMediaCollection('hotline');
            $path = storage_path("app/public/Hotline/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'hotline-form');
    }

    public function selectHotlineForEdit(int $id): void
    {
        $hotline = Hotline::findOrFail($id);
        $this->authorizeOwnerOrAdmin($hotline);

        $this->selectedId = $hotline->id;
        $this->title = $hotline->title;
        $this->phone = $hotline->phone ?? '';
        $this->link = $hotline->link ?? '';
        $this->status = $hotline->status ?? 'active';

        $this->dispatch('open-modal', 'hotline-form');
    }

    public function updateHotline(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $hotline = Hotline::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($hotline);

        $hotline->update([
            'title' => $this->title,
            'phone' => $this->phone ?: null,
            'link' => $this->link ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $hotline->addMediaFromUrl($this->image_url)->usingFileName($hotline->id. '.' . $extension)->toMediaCollection('hotline');
            $path = storage_path("app/public/Hotline/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo!=null) {
            $media = $hotline->addMedia($this->photo->getRealPath())->usingFileName($hotline->id. '.' . $this->photo->extension())->toMediaCollection('hotline');
            $path = storage_path("app/public/Hotline/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'hotline-form');
    }

    public function showDetails(int $id): void
    {
        $hotline = Hotline::with('user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($hotline, 'getFirstMediaUrl')) {
            $photoUrl = $hotline->getFirstMediaUrl('hotline', 'avatar') ?: $hotline->getFirstMediaUrl('hotline');
        }

        $this->hotlineDetails = [
            'title' => $hotline->title,
            'phone' => $hotline->phone,
            'link' => $hotline->link,
            'status' => $hotline->status,
            'photo_url' => $photoUrl,
            'created_by' => $hotline->user?->name,
            'created_at' => optional($hotline->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'hotline-details');
    }

    public function confirmDelete(int $id): void
    {
        $hotline = Hotline::findOrFail($id);
        $this->authorizeOwnerOrAdmin($hotline);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-hotline');
    }

    public function deleteSelectedHotline(): void
    {
        if (!$this->selectedId) return;
        $hotline = Hotline::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($hotline);
        $hotline->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-hotline');
    }

    public function openHotlineForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'hotline-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId','title','phone','link','status','photo','image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = Hotline::query()->where('status', 'active');
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', $s)
                  ->orWhere('phone', 'like', $s)
                  ->orWhere('link', 'like', $s);
            });
        }
        $hotlines = $query->latest()->get();

        return view('livewire.web.hotline.hotline-component', compact('hotlines'))
            ->layout('components.layouts.web');
    }
}

