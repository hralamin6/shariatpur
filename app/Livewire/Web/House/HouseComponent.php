<?php

namespace App\Livewire\Web\House;

use App\Models\House;
use App\Models\HouseType;
use App\Models\Upazila;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class HouseComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $house_type_id = null;
    public ?int $upazila_id = null;
    public string $name = '';
    public string $phone = '';
    public string $details = '';
    public string $map_one = '';
    public string $area = '';
    public int $room_number = 0;
    public int $bathroom_number = 0;
    public string $address = '';
    public int $rent_price = 0;
    public string $status = 'active';

    public string $search = '';
    public ?int $filter_type_id = null;
    public ?int $filter_upazila_id = null;

    public $photo; // uploaded file
    public string $image_url = '';

    public ?int $detailsId = null;
    public array $houseDetails = [];

    protected function rules(): array
    {
        return [
            'house_type_id' => 'required|exists:house_types,id',
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150|unique:houses,name,' . ($this->selectedId ?? 'NULL') . ',id',
            'phone' => 'nullable|string|max:30',
            'details' => 'nullable|string|max:1000',
            'map_one' => 'nullable|string|max:2000',
            'area' => 'nullable|string|max:100',
            'room_number' => 'nullable|integer|min:0|max:100',
            'bathroom_number' => 'nullable|integer|min:0|max:100',
            'address' => 'nullable|string|max:255',
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

    protected function authorizeOwnerOrAdmin(House $house): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($house->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createHouse(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $house = House::create([
            'user_id' => auth()->id(),
            'house_type_id' => $this->house_type_id,
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'details' => $this->details ?: null,
            'map_one' => $this->map_one ?: null,
            'area' => $this->area ?: null,
            'room_number' => $this->room_number ?: 0,
            'bathroom_number' => $this->bathroom_number ?: 0,
            'address' => $this->address ?: null,
            'rent_price' => $this->rent_price ?: 0,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $house->addMediaFromUrl($this->image_url)->usingFileName($house->id. '.' . $extension)->toMediaCollection('house');
            $path = storage_path("app/public/House/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $house->addMedia($this->photo->getRealPath())->usingFileName($house->name. '.' . $this->photo->extension())->toMediaCollection('house');
            $path = storage_path("app/public/House/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'house-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectHouseForEdit(int $id): void
    {
        $house = House::findOrFail($id);
        $this->authorizeOwnerOrAdmin($house);

        $this->selectedId = $house->id;
        $this->house_type_id = $house->house_type_id;
        $this->upazila_id = $house->upazila_id;
        $this->name = $house->name;
        $this->phone = $house->phone ?? '';
        $this->details = $house->details ?? '';
        $this->map_one = $house->map_one ?? '';
        $this->area = $house->area ?? '';
        $this->room_number = (int) ($house->room_number ?? 0);
        $this->bathroom_number = (int) ($house->bathroom_number ?? 0);
        $this->address = $house->address ?? '';
        $this->rent_price = (int) ($house->rent_price ?? 0);
        $this->status = $house->status ?? 'active';

        $this->dispatch('open-modal', 'house-form');
    }

    public function updateHouse(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $house = House::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($house);

        $house->update([
            'house_type_id' => $this->house_type_id,
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'details' => $this->details ?: null,
            'map_one' => $this->map_one ?: null,
            'area' => $this->area ?: null,
            'room_number' => $this->room_number ?: 0,
            'bathroom_number' => $this->bathroom_number ?: 0,
            'address' => $this->address ?: null,
            'rent_price' => $this->rent_price ?: 0,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $house->addMediaFromUrl($this->image_url)->usingFileName($house->id. '.' . $extension)->toMediaCollection('house');
            $path = storage_path("app/public/House/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $house->addMedia($this->photo->getRealPath())->usingFileName($house->name. '.' . $this->photo->extension())->toMediaCollection('house');
            $path = storage_path("app/public/House/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'house-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $house = House::findOrFail($id);
        $this->authorizeOwnerOrAdmin($house);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-house');
    }

    public function deleteSelectedHouse(): void
    {
        if (!$this->selectedId) return;
        $house = House::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($house);
        $house->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-house');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function openHouseForm(): void
    {
        $this->resetForm();
        if ($this->filter_type_id) {
            $this->house_type_id = $this->filter_type_id;
        }
        $this->dispatch('open-modal', 'house-form');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'selectedId','house_type_id','upazila_id','name','phone','details','map_one','area','room_number','bathroom_number','address','rent_price','status','photo','image_url'
        ]);
        $this->status = 'active';
    }

    public function mount($type_id = null): void
    {
        if ($type_id) {
            $this->filter_type_id = (int) $type_id;
            $this->house_type_id = (int) $type_id;
        }
    }

    public function render()
    {
        $query = House::with('type','upazila','user')->where('status','active');
        if ($this->filter_type_id) {
            $query->where('house_type_id', $this->filter_type_id);
        }
        if ($this->filter_upazila_id) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function($q) use($s) {
                $q->where('name','like',$s)
                  ->orWhere('phone','like',$s)
                  ->orWhere('details','like',$s)
                  ->orWhere('address','like',$s);
            });
        }
        $houses = $query->latest()->get();
        $types = HouseType::orderBy('name')->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.house.house-component', compact('houses','types','upazilas'))
            ->layout('components.layouts.web');
    }

    private function buildHouseDetailsPayload(House $house): array
    {
        $photoUrl = null;
        if (method_exists($house, 'getFirstMediaUrl')) {
            $photoUrl = $house->getFirstMediaUrl('house', 'avatar') ?: $house->getFirstMediaUrl('house');
        }
        return [
            'name' => $house->name,
            'type' => $house->type?->name,
            'upazila' => $house->upazila?->name,
            'area' => $house->area,
            'rooms' => (int) ($house->room_number ?? 0),
            'baths' => (int) ($house->bathroom_number ?? 0),
            'rent_price' => (int) ($house->rent_price ?? 0),
            'address' => $house->address,
            'map_one' => $house->map_one,
            'phone' => $house->phone,
            'details' => $house->details,
            'status' => $house->status,
            'photo_url' => $photoUrl,
            'created_by' => $house->user?->name,
            'created_at' => optional($house->created_at)->format('d M Y'),
        ];
    }

    public function showDetails(int $id): void
    {
        $house = House::with('type','upazila','user')->findOrFail($id);
        $this->detailsId = $id;
        $this->houseDetails = $this->buildHouseDetailsPayload($house);
        $this->dispatch('open-modal', 'house-details');
    }

    public function houseDetails(int $id): void
    {
        // Alias to showDetails to match requested method name
        $this->showDetails($id);
    }
}
