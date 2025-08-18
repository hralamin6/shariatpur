<?php

namespace App\Livewire\Web\Launch;

use App\Models\Launch;
use App\Models\LaunchRoute;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class LaunchComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $launch_route_id = null;
    public string $name = '';
    public string $phone = '';
    public string $details = '';
    public string $map_one = '';
    public string $map_two = '';
    public string $status = 'active';

    public string $search = '';
    public ?int $filter_route_id = null;

    // Image upload (URL or file)
    public $photo; // uploaded file
    public string $image_url = '';

    protected function rules(): array
    {
        return [
            'launch_route_id' => 'required|exists:launch_routes,id',
            'name' => 'required|string|max:150|unique:launches,name,' . ($this->selectedId ?? 'NULL') . ',id',
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

    protected function authorizeOwnerOrAdmin(Launch $launch): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($launch->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createLaunch(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $launch = Launch::create([
            'user_id' => auth()->id(),
            'launch_route_id' => $this->launch_route_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'details' => $this->details ?: null,
            'map_one' => $this->map_one ?: null,
            'map_two' => $this->map_two ?: null,
            'status' => $this->status,
        ]);

        // Media upload (URL or File)
        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $launch->addMediaFromUrl($this->image_url)->usingFileName($launch->id. '.' . $extension)->toMediaCollection('launch');
            $path = storage_path("app/public/Launch/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $launch->addMedia($this->photo->getRealPath())->usingFileName($launch->name. '.' . $this->photo->extension())->toMediaCollection('launch');
            $path = storage_path("app/public/Launch/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'launch-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectLaunchForEdit(int $id): void
    {
        $launch = Launch::findOrFail($id);
        $this->authorizeOwnerOrAdmin($launch);

        $this->selectedId = $launch->id;
        $this->launch_route_id = $launch->launch_route_id;
        $this->name = $launch->name;
        $this->phone = $launch->phone ?? '';
        $this->details = $launch->details ?? '';
        $this->map_one = $launch->map_one ?? '';
        $this->map_two = $launch->map_two ?? '';
        $this->status = $launch->status ?? 'active';

        $this->dispatch('open-modal', 'launch-form');
    }

    public function updateLaunch(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $launch = Launch::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($launch);

        $launch->update([
            'launch_route_id' => $this->launch_route_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'details' => $this->details ?: null,
            'map_one' => $this->map_one ?: null,
            'map_two' => $this->map_two ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $launch->addMediaFromUrl($this->image_url)->usingFileName($launch->id. '.' . $extension)->toMediaCollection('launch');
            $path = storage_path("app/public/Launch/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $launch->addMedia($this->photo->getRealPath())->usingFileName($launch->name. '.' . $this->photo->extension())->toMediaCollection('launch');
            $path = storage_path("app/public/Launch/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'launch-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $launch = Launch::findOrFail($id);
        $this->authorizeOwnerOrAdmin($launch);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-launch');
    }

    public function deleteSelectedLaunch(): void
    {
        if (!$this->selectedId) return;
        $launch = Launch::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($launch);
        $launch->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-launch');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function openLaunchForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'launch-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'launch_route_id', 'name', 'phone', 'details', 'map_one', 'map_two', 'status', 'photo', 'image_url']);
        $this->status = 'active';
        if ($this->filter_route_id) {
            $this->launch_route_id = $this->filter_route_id;
        }
    }

    public function mount($route_id = null): void
    {
        if ($route_id) {
            $this->filter_route_id = (int) $route_id;
            $this->launch_route_id = (int) $route_id;
        }
    }

    public function render()
    {
        $query = Launch::with('route', 'user')->where('status', 'active');
        if ($this->filter_route_id) {
            $query->where('launch_route_id', $this->filter_route_id);
        }
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('phone', 'like', $s)
                  ->orWhere('details', 'like', $s);
            });
        }
        $launches = $query->latest()->get();
        $routes = LaunchRoute::orderBy('name')->get();

        return view('livewire.web.launch.launch-component', compact('launches', 'routes'))
            ->layout('components.layouts.web');
    }
}

