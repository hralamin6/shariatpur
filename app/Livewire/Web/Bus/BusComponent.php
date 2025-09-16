<?php

namespace App\Livewire\Web\Bus;

use App\Models\Bus;
use App\Models\BusRoute;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class BusComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $bus_route_id = null;
    public string $name = '';
    public string $phone = '';
    public string $details = '';
    public string $map_one = '';
    public string $map_two = '';
    public string $status = 'active';

    public string $search = '';
    public ?int $filter_route_id = null;

    // Image upload like HospitalComponent
    public $photo; // uploaded file
    public string $image_url = '';

    // Details modal state
    public ?int $detailsId = null;
    public array $busDetails = [];

    protected function rules(): array
    {
        return [
            'bus_route_id' => 'required|exists:bus_routes,id',
            'name' => 'required|string|max:150|unique:buses,name,' . ($this->selectedId ?? 'NULL') . ',id',
            'phone' => 'nullable|string|max:30',
            'details' => 'nullable|string|max:1000',
            'map_one' => 'nullable|string|max:2000',
            'map_two' => 'nullable|string|max:2000',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(Bus $bus): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($bus->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function showDetails(int $id): void
    {
        $bus = Bus::with('route', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($bus, 'getFirstMediaUrl')) {
            $photoUrl = $bus->getFirstMediaUrl('bus', 'avatar') ?: $bus->getFirstMediaUrl('bus');
        }

        $this->busDetails = [
            'name' => $bus->name,
            'route' => $bus->route?->name,
            'phone' => $bus->phone,
            'details' => $bus->details,
            'map_one' => $bus->map_one,
            'map_two' => $bus->map_two,
            'status' => $bus->status,
            'photo_url' => $photoUrl,
            'created_by' => $bus->user?->name,
            'created_at' => optional($bus->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'bus-details');
    }

    public function createBus(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $bus = Bus::create([
            'user_id' => auth()->id(),
            'bus_route_id' => $this->bus_route_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'details' => $this->details ?: null,
            'map_one' => $this->map_one ?: null,
            'map_two' => $this->map_two ?: null,
            'status' => $this->status,
        ]);

        // Media upload (URL or File) like HospitalComponent
        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $bus->addMediaFromUrl($this->image_url)->usingFileName($bus->id. '.' . $extension)->toMediaCollection('bus');
            $path = storage_path("app/public/Bus/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $bus->addMedia($this->photo->getRealPath())->usingFileName($bus->name. '.' . $this->photo->extension())->toMediaCollection('bus');
            $path = storage_path("app/public/Bus/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'bus-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectBusForEdit(int $id): void
    {
        $bus = Bus::findOrFail($id);
        $this->authorizeOwnerOrAdmin($bus);

        $this->selectedId = $bus->id;
        $this->bus_route_id = $bus->bus_route_id;
        $this->name = $bus->name;
        $this->phone = $bus->phone ?? '';
        $this->details = $bus->details ?? '';
        $this->map_one = $bus->map_one ?? '';
        $this->map_two = $bus->map_two ?? '';
        $this->status = $bus->status ?? 'active';

        $this->dispatch('open-modal', 'bus-form');
    }

    public function updateBus(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $bus = Bus::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($bus);

        $bus->update([
            'bus_route_id' => $this->bus_route_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'details' => $this->details ?: null,
            'map_one' => $this->map_one ?: null,
            'map_two' => $this->map_two ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $bus->addMediaFromUrl($this->image_url)->usingFileName($bus->id. '.' . $extension)->toMediaCollection('bus');
            $path = storage_path("app/public/Bus/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $bus->addMedia($this->photo->getRealPath())->usingFileName($bus->name. '.' . $this->photo->extension())->toMediaCollection('bus');
            $path = storage_path("app/public/Bus/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'bus-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $bus = Bus::findOrFail($id);
        $this->authorizeOwnerOrAdmin($bus);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-bus');
    }

    public function deleteSelectedBus(): void
    {
        if (!$this->selectedId) return;
        $bus = Bus::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($bus);
        $bus->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-bus');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function openBusForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'bus-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'bus_route_id', 'name', 'phone', 'details', 'map_one', 'map_two', 'status', 'photo', 'image_url', 'detailsId', 'busDetails']);
        $this->status = 'active';
        if ($this->filter_route_id) {
            $this->bus_route_id = $this->filter_route_id;
        }
    }

    public function mount($route_id = null): void
    {
        if ($route_id) {
            $this->filter_route_id = (int) $route_id;
            $this->bus_route_id = (int) $route_id;
        }
    }

    public function render()
    {
        $query = Bus::with('route', 'user')->where('status', 'active');
        if ($this->filter_route_id) {
            $query->where('bus_route_id', $this->filter_route_id);
        }
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('phone', 'like', $s)
                  ->orWhere('details', 'like', $s);
            });
        }
        $buses = $query->latest()->get();
        $routes = BusRoute::orderBy('name')->get();

        return view('livewire.web.bus.bus-component', compact('buses', 'routes'))
            ->layout('components.layouts.web');
    }
}

