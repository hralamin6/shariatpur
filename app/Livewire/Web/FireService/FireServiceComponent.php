<?php

namespace App\Livewire\Web\FireService;

use App\Models\FireService;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class FireServiceComponent extends Component
{
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $upazila_id = null;
    public string $name = '';
    public string $phone = '';
    public string $address = '';
    public string $map = '';
    public string $status = 'active';

    public string $search = '';
    public ?int $filter_upazila_id = null;

    // Photo upload like HospitalComponent
    public $photo; // uploaded file
    public string $image_url = '';

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150|unique:fire_services,name,' . ($this->selectedId ?? 'NULL') . ',id',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'map' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(FireService $fs): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($fs->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createFireService(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $fs = FireService::create([
            'user_id' => auth()->id(),
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $fs->addMediaFromUrl($this->image_url)->usingFileName($fs->id. '.' . $extension)->toMediaCollection('fireservice');
            $path = storage_path("app/public/Fireservice/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $fs->addMedia($this->photo->getRealPath())->usingFileName($fs->name. '.' . $this->photo->extension())->toMediaCollection('fireservice');
            $path = storage_path("app/public/Fireservice/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'fire-service-form');
    }

    public function selectFireServiceForEdit(int $id): void
    {
        $fs = FireService::findOrFail($id);
        $this->authorizeOwnerOrAdmin($fs);

        $this->selectedId = $fs->id;
        $this->upazila_id = $fs->upazila_id;
        $this->name = $fs->name;
        $this->phone = $fs->phone ?? '';
        $this->address = $fs->address ?? '';
        $this->map = $fs->map ?? '';
        $this->status = $fs->status ?? 'active';

        $this->dispatch('open-modal', 'fire-service-form');
    }

    public function updateFireService(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $fs = FireService::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($fs);

        $fs->update([
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $fs->addMediaFromUrl($this->image_url)->usingFileName($fs->id. '.' . $extension)->toMediaCollection('fireservice');
            $path = storage_path("app/public/Fireservice/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $fs->addMedia($this->photo->getRealPath())->usingFileName($fs->name. '.' . $this->photo->extension())->toMediaCollection('fireservice');
            $path = storage_path("app/public/Fireservice/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'fire-service-form');
    }

    public function confirmDelete(int $id): void
    {
        $fs = FireService::findOrFail($id);
        $this->authorizeOwnerOrAdmin($fs);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-fire-service');
    }

    public function deleteSelectedFireService(): void
    {
        if (!$this->selectedId) return;
        $fs = FireService::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($fs);
        $fs->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-fire-service');
    }

    public function openFireServiceForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'fire-service-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'upazila_id', 'name', 'phone', 'address', 'map', 'status', 'photo', 'image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = FireService::with('upazila', 'user')->where('status', 'active');
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('address', 'like', $s)
                  ->orWhere('phone', 'like', $s);
            });
        }
        if (!empty($this->filter_upazila_id)) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        $fireServices = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.fireservice.fire-service-component', compact('fireServices', 'upazilas'))
            ->layout('components.layouts.web');
    }
}

