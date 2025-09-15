<?php

namespace App\Livewire\Web\Car;

use App\Models\Car;
use App\Models\CarType;
use App\Models\Upazila;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class CarComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $car_type_id = null;
    public ?int $upazila_id = null;
    public string $name = '';
    public string $driver_name = '';
    public string $phone = '';
    public bool $ac = false;
    public ?int $seat_number = 0;
    public ?int $weight_capacity = 0;
    public string $address = '';
    public string $map = '';
    public ?int $rent_price = 0;
    public string $status = 'active';

    public string $search = '';
    public ?int $filter_type_id = null;
    public ?int $filter_upazila_id = null;

    public $photo; // uploaded file
    public string $image_url = '';

    // Details modal state
    public ?int $detailsId = null;
    public array $carDetails = [];

    protected function rules(): array
    {
        return [
            'car_type_id' => 'required|exists:car_types,id',
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150|unique:cars,name,' . ($this->selectedId ?? 'NULL') . ',id',
            'driver_name' => 'nullable|string|max:150',
            'phone' => 'nullable|string|max:30',
            'ac' => 'boolean',
            'seat_number' => 'nullable|integer|min:0|max:100',
            'weight_capacity' => 'nullable|integer|min:0|max:10000',
            'address' => 'nullable|string|max:255',
            'map' => 'nullable|string|max:2000',
            'rent_price' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:4096',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(Car $car): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($car->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createCar(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $car = Car::create([
            'user_id' => auth()->id(),
            'car_type_id' => $this->car_type_id,
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'driver_name' => $this->driver_name ?: null,
            'phone' => $this->phone ?: null,
            'ac' => (bool) $this->ac,
            'seat_number' => $this->seat_number ?: 0,
            'weight_capacity' => $this->weight_capacity ?: 0,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'rent_price' => $this->rent_price ?: 0,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $car->addMediaFromUrl($this->image_url)->usingFileName($car->id. '.' . $extension)->toMediaCollection('car');
            $path = storage_path("app/public/Car/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        } elseif ($this->photo!=null) {
            $media = $car->addMedia($this->photo->getRealPath())->usingFileName($car->name. '.' . $this->photo->extension())->toMediaCollection('car');
            $path = storage_path("app/public/Car/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'car-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectCarForEdit(int $id): void
    {
        $car = Car::findOrFail($id);
        $this->authorizeOwnerOrAdmin($car);

        $this->selectedId = $car->id;
        $this->car_type_id = $car->car_type_id;
        $this->upazila_id = $car->upazila_id;
        $this->name = $car->name;
        $this->driver_name = $car->driver_name ?? '';
        $this->phone = $car->phone ?? '';
        $this->ac = (bool) ($car->ac ?? false);
        $this->seat_number = (int) ($car->seat_number ?? 0);
        $this->weight_capacity = (int) ($car->weight_capacity ?? 0);
        $this->address = $car->address ?? '';
        $this->map = $car->map ?? '';
        $this->rent_price = (int) ($car->rent_price ?? 0);
        $this->status = $car->status ?? 'active';

        $this->dispatch('open-modal', 'car-form');
    }

    public function updateCar(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $car = Car::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($car);

        $car->update([
            'car_type_id' => $this->car_type_id,
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'driver_name' => $this->driver_name ?: null,
            'phone' => $this->phone ?: null,
            'ac' => (bool) $this->ac,
            'seat_number' => $this->seat_number ?: 0,
            'weight_capacity' => $this->weight_capacity ?: 0,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'rent_price' => $this->rent_price ?: 0,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $car->addMediaFromUrl($this->image_url)->usingFileName($car->id. '.' . $extension)->toMediaCollection('car');
            $path = storage_path("app/public/Car/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        } elseif ($this->photo!=null) {
            $media = $car->addMedia($this->photo->getRealPath())->usingFileName($car->name. '.' . $this->photo->extension())->toMediaCollection('car');
            $path = storage_path("app/public/Car/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'car-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $car = Car::findOrFail($id);
        $this->authorizeOwnerOrAdmin($car);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-car');
    }

    public function deleteSelectedCar(): void
    {
        if (!$this->selectedId) return;
        $car = Car::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($car);
        $car->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-car');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function openCarForm(): void
    {
        $this->resetForm();
        if ($this->filter_type_id) {
            $this->car_type_id = $this->filter_type_id;
        }
        $this->dispatch('open-modal', 'car-form');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'selectedId','car_type_id','upazila_id','name','driver_name','phone','ac','seat_number','weight_capacity','address','map','rent_price','status','photo','image_url'
        ]);
        $this->status = 'active';
        $this->ac = false;
    }

    private function buildCarDetailsPayload(Car $car): array
    {
        $photoUrl = null;
        if (method_exists($car, 'getFirstMediaUrl')) {
            $photoUrl = $car->getFirstMediaUrl('car', 'avatar') ?: $car->getFirstMediaUrl('car');
        }

        return [
            'name' => $car->name,
            'type' => $car->type?->name,
            'upazila' => $car->upazila?->name,
            'ac' => (bool) ($car->ac ?? false),
            'seats' => (int) ($car->seat_number ?? 0),
            'capacity' => (int) ($car->weight_capacity ?? 0),
            'rent_price' => (int) ($car->rent_price ?? 0),
            'address' => $car->address,
            'map' => $car->map,
            'phone' => $car->phone,
            'driver_name' => $car->driver_name,
            'status' => $car->status,
            'photo_url' => $photoUrl,
            'created_by' => $car->user?->name,
            'created_at' => optional($car->created_at)->format('d M Y'),
        ];
    }

    public function showDetails(int $id): void
    {
        $car = Car::with('type','upazila','user')->findOrFail($id);
        $this->detailsId = $id;
        $this->carDetails = $this->buildCarDetailsPayload($car);
        $this->dispatch('open-modal', 'car-details');
    }

    public function render()
    {
        $query = Car::with('type','upazila','user')->where('status','active');
        if ($this->filter_type_id) {
            $query->where('car_type_id', $this->filter_type_id);
        }
        if ($this->filter_upazila_id) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function($q) use($s) {
                $q->where('name','like',$s)
                  ->orWhere('driver_name','like',$s)
                  ->orWhere('phone','like',$s)
                  ->orWhere('address','like',$s);
            });
        }
        $cars = $query->latest()->get();
        $types = CarType::orderBy('name')->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.car.car-component', compact('cars','types','upazilas'))
            ->layout('components.layouts.web');
    }
}
