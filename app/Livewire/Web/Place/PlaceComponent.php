<?php

namespace App\Livewire\Web\Place;

use App\Models\Place;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class PlaceComponent extends Component
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

    // Photo upload (URL or File)
    public $photo; // uploaded file
    public string $image_url = '';

    public ?int $detailsId = null;
    public array $placeDetails = [];

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150|unique:places,name,' . ($this->selectedId ?? 'NULL') . ',id',
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

    protected function authorizeOwnerOrAdmin(Place $place): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($place->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createPlace(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $place = Place::create([
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
            $media =  $place->addMediaFromUrl($this->image_url)->usingFileName($place->id. '.' . $extension)->toMediaCollection('place');
            $path = storage_path("app/public/Place/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $place->addMedia($this->photo->getRealPath())->usingFileName($place->name. '.' . $this->photo->extension())->toMediaCollection('place');
            $path = storage_path("app/public/Place/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'place-form');
    }

    public function selectPlaceForEdit(int $id): void
    {
        $place = Place::findOrFail($id);
        $this->authorizeOwnerOrAdmin($place);

        $this->selectedId = $place->id;
        $this->upazila_id = $place->upazila_id;
        $this->name = $place->name;
        $this->phone = $place->phone ?? '';
        $this->address = $place->address ?? '';
        $this->map = $place->map ?? '';
        $this->details = $place->details ?? '';
        $this->status = $place->status ?? 'active';

        $this->dispatch('open-modal', 'place-form');
    }

    public function showDetails(int $id): void
    {
        $place = Place::with('upazila', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($place, 'getFirstMediaUrl')) {
            $photoUrl = $place->getFirstMediaUrl('place', 'avatar') ?: $place->getFirstMediaUrl('place');
        }

        $this->placeDetails = [
            'name' => $place->name,
            'upazila' => $place->upazila?->name,
            'address' => $place->address,
            'map' => $place->map,
            'phone' => $place->phone,
            'details' => $place->details,
            'status' => $place->status,
            'photo_url' => $photoUrl,
            'created_by' => $place->user?->name,
            'created_at' => optional($place->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'place-details');
    }

    public function updatePlace(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $place = Place::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($place);

        $place->update([
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
            $media =  $place->addMediaFromUrl($this->image_url)->usingFileName($place->id. '.' . $extension)->toMediaCollection('place');
            $path = storage_path("app/public/Place/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $place->addMedia($this->photo->getRealPath())->usingFileName($place->name. '.' . $this->photo->extension())->toMediaCollection('place');
            $path = storage_path("app/public/Place/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'place-form');
    }

    public function confirmDelete(int $id): void
    {
        $place = Place::findOrFail($id);
        $this->authorizeOwnerOrAdmin($place);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-place');
    }

    public function deleteSelectedPlace(): void
    {
        if (!$this->selectedId) return;
        $place = Place::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($place);
        $place->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-place');
    }

    public function openPlaceForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'place-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'upazila_id', 'name', 'phone', 'address', 'map', 'details', 'status', 'photo', 'image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = Place::with('upazila', 'user')->where('status', 'active');
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
        $places = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.place.place-component', compact('places', 'upazilas'))
            ->layout('components.layouts.web');
    }
}

