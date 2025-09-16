<?php

namespace App\Livewire\Web\Restaurant;

use App\Models\Restaurant;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class RestaurantComponent extends Component
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
    public array $restaurantDetails = [];

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150|unique:restaurants,name,' . ($this->selectedId ?? 'NULL') . ',id',
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

    protected function authorizeOwnerOrAdmin(Restaurant $restaurant): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($restaurant->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createRestaurant(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $restaurant = Restaurant::create([
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
            $media =  $restaurant->addMediaFromUrl($this->image_url)->usingFileName($restaurant->id. '.' . $extension)->toMediaCollection('restaurant');
            $path = storage_path("app/public/Restaurant/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        } elseif ($this->photo!=null) {
            $media = $restaurant->addMedia($this->photo->getRealPath())->usingFileName($restaurant->name. '.' . $this->photo->extension())->toMediaCollection('restaurant');
            $path = storage_path("app/public/Restaurant/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'restaurant-form');
    }

    public function selectRestaurantForEdit(int $id): void
    {
        $restaurant = Restaurant::findOrFail($id);
        $this->authorizeOwnerOrAdmin($restaurant);

        $this->selectedId = $restaurant->id;
        $this->upazila_id = $restaurant->upazila_id;
        $this->name = $restaurant->name;
        $this->phone = $restaurant->phone ?? '';
        $this->address = $restaurant->address ?? '';
        $this->map = $restaurant->map ?? '';
        $this->details = $restaurant->details ?? '';
        $this->status = $restaurant->status ?? 'active';

        $this->dispatch('open-modal', 'restaurant-form');
    }

    public function showDetails(int $id): void
    {
        $restaurant = Restaurant::with('upazila', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($restaurant, 'getFirstMediaUrl')) {
            $photoUrl = $restaurant->getFirstMediaUrl('restaurant', 'avatar') ?: $restaurant->getFirstMediaUrl('restaurant');
        }

        $this->restaurantDetails = [
            'name' => $restaurant->name,
            'upazila' => $restaurant->upazila?->name,
            'address' => $restaurant->address,
            'map' => $restaurant->map,
            'phone' => $restaurant->phone,
            'details' => $restaurant->details,
            'status' => $restaurant->status,
            'photo_url' => $photoUrl,
            'created_by' => $restaurant->user?->name,
            'created_at' => optional($restaurant->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'restaurant-details');
    }

    public function updateRestaurant(): void
    {
        if (!$this->selectedId) { return; }
        $this->validate();

        $restaurant = Restaurant::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($restaurant);

        $restaurant->update([
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
            $media =  $restaurant->addMediaFromUrl($this->image_url)->usingFileName($restaurant->id. '.' . $extension)->toMediaCollection('restaurant');
            $path = storage_path("app/public/Restaurant/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        } elseif ($this->photo!=null) {
            $media = $restaurant->addMedia($this->photo->getRealPath())->usingFileName($restaurant->name. '.' . $this->photo->extension())->toMediaCollection('restaurant');
            $path = storage_path("app/public/Restaurant/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'restaurant-form');
    }

    public function confirmDelete(int $id): void
    {
        $restaurant = Restaurant::findOrFail($id);
        $this->authorizeOwnerOrAdmin($restaurant);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-restaurant');
    }

    public function deleteSelectedRestaurant(): void
    {
        if (!$this->selectedId) { return; }
        $restaurant = Restaurant::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($restaurant);
        $restaurant->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-restaurant');
    }

    public function openRestaurantForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'restaurant-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'upazila_id', 'name', 'phone', 'address', 'map', 'details', 'status', 'photo', 'image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = Restaurant::with('upazila', 'user')->where('status', 'active');
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
        $restaurants = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.restaurant.restaurant-component', compact('restaurants', 'upazilas'))
            ->layout('components.layouts.web');
    }
}

