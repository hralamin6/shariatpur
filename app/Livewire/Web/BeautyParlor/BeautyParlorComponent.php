<?php

namespace App\Livewire\Web\BeautyParlor;

use App\Models\BeautyParlor;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class BeautyParlorComponent extends Component
{
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $upazila_id = null;
    public string $name = '';
    public string $phone = '';
    public string $address = '';
    public string $map = '';
    public string $details = '';
    public string $status = 'active';

    public string $search = '';
    public ?int $filter_upazila_id = null;

    public $photo; // uploaded file
    public string $image_url = '';

    public ?int $detailsId = null;
    public array $parlorDetails = [];

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150|unique:beauty_parlors,name,' . ($this->selectedId ?? 'NULL') . ',id',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'map' => 'nullable|string|max:1000',
            'details' => 'nullable|string|max:2000',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(BeautyParlor $parlor): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($parlor->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createParlor(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $parlor = BeautyParlor::create([
            'user_id' => auth()->id(),
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $parlor->addMediaFromUrl($this->image_url)->usingFileName($parlor->id. '.' . $extension)->toMediaCollection('beauty_parlor');
            $path = storage_path("app/public/BeautyParlor/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo!=null) {
            $media = $parlor->addMedia($this->photo->getRealPath())->usingFileName($parlor->name. '.' . $this->photo->extension())->toMediaCollection('beauty_parlor');
            $path = storage_path("app/public/BeautyParlor/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'parlor-form');
    }

    public function selectParlorForEdit(int $id): void
    {
        $parlor = BeautyParlor::findOrFail($id);
        $this->authorizeOwnerOrAdmin($parlor);

        $this->selectedId = $parlor->id;
        $this->upazila_id = $parlor->upazila_id;
        $this->name = $parlor->name;
        $this->phone = $parlor->phone ?? '';
        $this->address = $parlor->address ?? '';
        $this->map = $parlor->map ?? '';
        $this->details = $parlor->details ?? '';
        $this->status = $parlor->status ?? 'active';

        $this->dispatch('open-modal', 'parlor-form');
    }

    public function showDetails(int $id): void
    {
        $parlor = BeautyParlor::with('upazila', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($parlor, 'getFirstMediaUrl')) {
            $photoUrl = $parlor->getFirstMediaUrl('beauty_parlor', 'avatar') ?: $parlor->getFirstMediaUrl('beauty_parlor');
        }

        $this->parlorDetails = [
            'name' => $parlor->name,
            'upazila' => $parlor->upazila?->name,
            'address' => $parlor->address,
            'map' => $parlor->map,
            'phone' => $parlor->phone,
            'details' => $parlor->details,
            'status' => $parlor->status,
            'photo_url' => $photoUrl,
            'created_by' => $parlor->user?->name,
            'created_at' => optional($parlor->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'parlor-details');
    }

    public function updateParlor(): void
    {
        if (!$this->selectedId) { return; }
        $this->validate();

        $parlor = BeautyParlor::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($parlor);

        $parlor->update([
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $parlor->addMediaFromUrl($this->image_url)->usingFileName($parlor->id. '.' . $extension)->toMediaCollection('beauty_parlor');
            $path = storage_path("app/public/BeautyParlor/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo!=null) {
            $media = $parlor->addMedia($this->photo->getRealPath())->usingFileName($parlor->name. '.' . $this->photo->extension())->toMediaCollection('beauty_parlor');
            $path = storage_path("app/public/BeautyParlor/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'parlor-form');
    }

    public function confirmDelete(int $id): void
    {
        $parlor = BeautyParlor::findOrFail($id);
        $this->authorizeOwnerOrAdmin($parlor);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-parlor');
    }

    public function deleteSelectedParlor(): void
    {
        if (!$this->selectedId) { return; }
        $parlor = BeautyParlor::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($parlor);
        $parlor->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-parlor');
    }

    public function openParlorForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'parlor-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'upazila_id', 'name', 'phone', 'address', 'map', 'details', 'status', 'photo', 'image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = BeautyParlor::with('upazila', 'user')->where('status', 'active');
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('address', 'like', $s)
                  ->orWhere('phone', 'like', $s)
                  ->orWhere('details', 'like', $s);
            });
        }
        if (!empty($this->filter_upazila_id)) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        $parlors = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.beauty-parlor.beauty-parlor-component', compact('parlors', 'upazilas'))
            ->layout('components.layouts.web');
    }
}

