<?php

namespace App\Livewire\Web\Hotel;

use App\Models\Hotel;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class HotelComponent extends Component
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
    public array $hotelDetails = [];

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150|unique:hotels,name,' . ($this->selectedId ?? 'NULL') . ',id',
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

    protected function authorizeOwnerOrAdmin(Hotel $hotel): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($hotel->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createHotel(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $hotel = Hotel::create([
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
            $media =  $hotel->addMediaFromUrl($this->image_url)->usingFileName($hotel->id. '.' . $extension)->toMediaCollection('hotel');
            $path = storage_path("app/public/Hotel/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $hotel->addMedia($this->photo->getRealPath())->usingFileName($hotel->name. '.' . $this->photo->extension())->toMediaCollection('hotel');
            $path = storage_path("app/public/Hotel/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'hotel-form');
    }

    public function selectHotelForEdit(int $id): void
    {
        $hotel = Hotel::findOrFail($id);
        $this->authorizeOwnerOrAdmin($hotel);

        $this->selectedId = $hotel->id;
        $this->upazila_id = $hotel->upazila_id;
        $this->name = $hotel->name;
        $this->phone = $hotel->phone ?? '';
        $this->address = $hotel->address ?? '';
        $this->map = $hotel->map ?? '';
        $this->details = $hotel->details ?? '';
        $this->status = $hotel->status ?? 'active';

        $this->dispatch('open-modal', 'hotel-form');
    }

    public function showDetails(int $id): void
    {
        $hotel = Hotel::with('upazila', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($hotel, 'getFirstMediaUrl')) {
            $photoUrl = $hotel->getFirstMediaUrl('hotel', 'avatar') ?: $hotel->getFirstMediaUrl('hotel');
        }

        $this->hotelDetails = [
            'name' => $hotel->name,
            'upazila' => $hotel->upazila?->name,
            'address' => $hotel->address,
            'map' => $hotel->map,
            'phone' => $hotel->phone,
            'details' => $hotel->details,
            'status' => $hotel->status,
            'photo_url' => $photoUrl,
            'created_by' => $hotel->user?->name,
            'created_at' => optional($hotel->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'hotel-details');
    }

    public function updateHotel(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $hotel = Hotel::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($hotel);

        $hotel->update([
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
            $media =  $hotel->addMediaFromUrl($this->image_url)->usingFileName($hotel->id. '.' . $extension)->toMediaCollection('hotel');
            $path = storage_path("app/public/Hotel/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $hotel->addMedia($this->photo->getRealPath())->usingFileName($hotel->name. '.' . $this->photo->extension())->toMediaCollection('hotel');
            $path = storage_path("app/public/Hotel/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'hotel-form');
    }

    public function confirmDelete(int $id): void
    {
        $hotel = Hotel::findOrFail($id);
        $this->authorizeOwnerOrAdmin($hotel);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-hotel');
    }

    public function deleteSelectedHotel(): void
    {
        if (!$this->selectedId) return;
        $hotel = Hotel::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($hotel);
        $hotel->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-hotel');
    }

    public function openHotelForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'hotel-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'upazila_id', 'name', 'phone', 'address', 'map', 'details', 'status', 'photo', 'image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = Hotel::with('upazila', 'user')->where('status', 'active');
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
        $hotels = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.hotel.hotel-component', compact('hotels', 'upazilas'))
            ->layout('components.layouts.web');
    }
}

