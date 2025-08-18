<?php

namespace App\Livewire\Web\Hospital;

use App\Models\Hospital;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class HospitalComponent extends Component
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

    // Photo upload like DoctorComponent
    public $photo; // uploaded file
    public string $image_url = '';

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150|unique:hospitals,name,' . ($this->selectedId ?? 'NULL') . ',id',
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

    protected function authorizeOwnerOrAdmin(Hospital $hospital): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($hospital->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createHospital(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $hospital = Hospital::create([
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
            $media =  $hospital->addMediaFromUrl($this->image_url)->usingFileName($hospital->id. '.' . $extension)->toMediaCollection('hospital');
            $path = storage_path("app/public/Hospital/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $hospital->addMedia($this->photo->getRealPath())->usingFileName($hospital->name. '.' . $this->photo->extension())->toMediaCollection('hospital');
            $path = storage_path("app/public/Hospital/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'hospital-form');
    }

    public function selectHospitalForEdit(int $id): void
    {
        $hospital = Hospital::findOrFail($id);
        $this->authorizeOwnerOrAdmin($hospital);

        $this->selectedId = $hospital->id;
        $this->upazila_id = $hospital->upazila_id;
        $this->name = $hospital->name;
        $this->phone = $hospital->phone ?? '';
        $this->address = $hospital->address ?? '';
        $this->map = $hospital->map ?? '';
        $this->status = $hospital->status ?? 'active';

        $this->dispatch('open-modal', 'hospital-form');
    }

    public function updateHospital(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $hospital = Hospital::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($hospital);

        $hospital->update([
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $hospital->addMediaFromUrl($this->image_url)->usingFileName($hospital->id. '.' . $extension)->toMediaCollection('hospital');
            $path = storage_path("app/public/Hospital/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $hospital->addMedia($this->photo->getRealPath())->usingFileName($hospital->name. '.' . $this->photo->extension())->toMediaCollection('hospital');
            $path = storage_path("app/public/Hospital/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'hospital-form');
    }

    public function confirmDelete(int $id): void
    {
        $hospital = Hospital::findOrFail($id);
        $this->authorizeOwnerOrAdmin($hospital);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-hospital');
    }

    public function deleteSelectedHospital(): void
    {
        if (!$this->selectedId) return;
        $hospital = Hospital::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($hospital);
        $hospital->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-hospital');
    }

    public function openHospitalForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'hospital-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'upazila_id', 'name', 'phone', 'address', 'map', 'status', 'photo', 'image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = Hospital::with('upazila', 'user')->where('status', 'active');
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
        $hospitals = $query->latest()->get();
        //get upazila where id is 322 to 427
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.hospital.hospital-component', compact('hospitals', 'upazilas'))
            ->layout('components.layouts.web');
    }
}
