<?php

namespace App\Livewire\Web\LostFound;

use App\Models\LostFound;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class LostFoundComponent extends Component
{
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $upazila_id = null;
    public string $title = '';
    public string $type = 'lost'; // lost or found
    public string $item = '';
    public ?string $date = null; // Y-m-d
    public string $address = '';
    public string $phone = '';
    public string $map = '';
    public string $details = '';
    public string $status = 'active';

    public string $search = '';
    public ?int $filter_upazila_id = null;
    public ?string $filter_type = null; // lost|found

    public $photo; // uploaded file
    public string $image_url = '';

    public ?int $detailsId = null;
    public array $entryDetails = [];

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'title' => 'required|string|max:180',
            'type' => 'required|in:lost,found',
            'item' => 'nullable|string|max:160',
            'date' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:30',
            'map' => 'nullable|string|max:1000',
            'details' => 'nullable|string|max:2000',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url|max:1000',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(LostFound $entry): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($entry->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createEntry(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $entry = LostFound::create([
            'user_id' => auth()->id(),
            'upazila_id' => $this->upazila_id,
            'title' => $this->title,
            'type' => $this->type,
            'item' => $this->item ?: null,
            'date' => $this->date ?: null,
            'address' => $this->address ?: null,
            'phone' => $this->phone ?: null,
            'map' => $this->map ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if (!empty($this->image_url) && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $entry->addMediaFromUrl($this->image_url)->usingFileName($entry->id. '.' . $extension)->toMediaCollection('lostfound');
            $path = storage_path('app/public/LostFound/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) { unlink($path); }
        } elseif ($this->photo) {
            $media = $entry->addMedia($this->photo->getRealPath())->usingFileName(($this->title ?: 'lostfound'). '.' . $this->photo->extension())->toMediaCollection('lostfound');
            $path = storage_path('app/public/LostFound/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) { unlink($path); }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'lostfound-form');
    }

    public function selectForEdit(int $id): void
    {
        $entry = LostFound::findOrFail($id);
        $this->authorizeOwnerOrAdmin($entry);

        $this->selectedId = $entry->id;
        $this->upazila_id = $entry->upazila_id;
        $this->title = $entry->title;
        $this->type = $entry->type;
        $this->item = $entry->item ?? '';
        $this->date = optional($entry->date)?->format('Y-m-d');
        $this->address = $entry->address ?? '';
        $this->phone = $entry->phone ?? '';
        $this->map = $entry->map ?? '';
        $this->details = $entry->details ?? '';
        $this->status = $entry->status ?? 'active';

        $this->dispatch('open-modal', 'lostfound-form');
    }

    public function showDetails(int $id): void
    {
        $entry = LostFound::with('upazila', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($entry, 'getFirstMediaUrl')) {
            $photoUrl = $entry->getFirstMediaUrl('lostfound', 'avatar') ?: $entry->getFirstMediaUrl('lostfound');
        }

        $this->entryDetails = [
            'title' => $entry->title,
            'type' => $entry->type,
            'item' => $entry->item,
            'date' => optional($entry->date)?->format('d M Y'),
            'upazila' => $entry->upazila?->name,
            'address' => $entry->address,
            'map' => $entry->map,
            'phone' => $entry->phone,
            'details' => $entry->details,
            'status' => $entry->status,
            'photo_url' => $photoUrl,
            'created_by' => $entry->user?->name,
            'created_at' => optional($entry->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'lostfound-details');
    }

    public function updateEntry(): void
    {
        if (!$this->selectedId) { return; }
        $this->validate();

        $entry = LostFound::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($entry);

        $entry->update([
            'upazila_id' => $this->upazila_id,
            'title' => $this->title,
            'type' => $this->type,
            'item' => $this->item ?: null,
            'date' => $this->date ?: null,
            'address' => $this->address ?: null,
            'phone' => $this->phone ?: null,
            'map' => $this->map ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if (!empty($this->image_url) && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $entry->addMediaFromUrl($this->image_url)->usingFileName($entry->id. '.' . $extension)->toMediaCollection('lostfound');
            $path = storage_path('app/public/LostFound/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) { unlink($path); }
        } elseif ($this->photo) {
            $media = $entry->addMedia($this->photo->getRealPath())->usingFileName(($this->title ?: 'lostfound'). '.' . $this->photo->extension())->toMediaCollection('lostfound');
            $path = storage_path('app/public/LostFound/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) { unlink($path); }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'lostfound-form');
    }

    public function confirmDelete(int $id): void
    {
        $entry = LostFound::findOrFail($id);
        $this->authorizeOwnerOrAdmin($entry);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-lostfound');
    }

    public function deleteSelected(): void
    {
        if (!$this->selectedId) { return; }
        $entry = LostFound::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($entry);
        $entry->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-lostfound');
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'lostfound-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId','upazila_id','title','type','item','date','address','phone','map','details','status','photo','image_url']);
        $this->status = 'active';
        $this->type = 'lost';
    }

    public function render()
    {
        $query = LostFound::with('upazila', 'user')->where('status', 'active');
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', $s)
                  ->orWhere('item', 'like', $s)
                  ->orWhere('address', 'like', $s)
                  ->orWhere('phone', 'like', $s)
                  ->orWhere('details', 'like', $s);
            });
        }
        if (!empty($this->filter_upazila_id)) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        if (!empty($this->filter_type)) {
            $query->where('type', $this->filter_type);
        }
        $entries = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.lostfound.lostfound-component', compact('entries', 'upazilas'))
            ->layout('components.layouts.web');
    }
}

