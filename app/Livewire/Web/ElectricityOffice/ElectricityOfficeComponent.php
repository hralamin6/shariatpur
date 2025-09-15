<?php

namespace App\Livewire\Web\ElectricityOffice;

use App\Models\ElectricityOffice;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class ElectricityOfficeComponent extends Component
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

    // Photo upload
    public $photo; // uploaded file

    public string $image_url = '';

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150|unique:electricity_offices,name,'.($this->selectedId ?? 'NULL').',id',
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

    protected function authorizeOwnerOrAdmin(ElectricityOffice $eo): void
    {
        if (! auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($eo->user_id !== auth()->id() && ! $this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createElectricityOffice(): void
    {
        if (! auth()->check()) {
            redirect()->route('login')->send();

            return;
        }
        $this->validate();

        $eo = ElectricityOffice::create([
            'user_id' => auth()->id(),
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url != null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $eo->addMediaFromUrl($this->image_url)->usingFileName($eo->id.'.'.$extension)->toMediaCollection('electricityoffice');
            $path = storage_path('app/public/Electricityoffice/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        } elseif ($this->photo != null) {
            $media = $eo->addMedia($this->photo->getRealPath())->usingFileName($eo->name.'.'.$this->photo->extension())->toMediaCollection('electricityoffice');
            $path = storage_path('app/public/Electricityoffice/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'electricity-office-form');
    }

    public function selectElectricityOfficeForEdit(int $id): void
    {
        $eo = ElectricityOffice::findOrFail($id);
        $this->authorizeOwnerOrAdmin($eo);

        $this->selectedId = $eo->id;
        $this->upazila_id = $eo->upazila_id;
        $this->name = $eo->name;
        $this->phone = $eo->phone ?? '';
        $this->address = $eo->address ?? '';
        $this->map = $eo->map ?? '';
        $this->status = $eo->status ?? 'active';

        $this->dispatch('open-modal', 'electricity-office-form');
    }

    public function updateElectricityOffice(): void
    {
        if (! $this->selectedId) {
            return;
        }
        $this->validate();

        $eo = ElectricityOffice::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($eo);

        $eo->update([
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url != null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $eo->addMediaFromUrl($this->image_url)->usingFileName($eo->id.'.'.$extension)->toMediaCollection('electricityoffice');
            $path = storage_path('app/public/Electricityoffice/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        } elseif ($this->photo != null) {
            $media = $eo->addMedia($this->photo->getRealPath())->usingFileName($eo->name.'.'.$this->photo->extension())->toMediaCollection('electricityoffice');
            $path = storage_path('app/public/Electricityoffice/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'electricity-office-form');
    }

    public function confirmDelete(int $id): void
    {
        $eo = ElectricityOffice::findOrFail($id);
        $this->authorizeOwnerOrAdmin($eo);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-electricity-office');
    }

    public function deleteSelectedElectricityOffice(): void
    {
        if (! $this->selectedId) {
            return;
        }
        $eo = ElectricityOffice::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($eo);
        $eo->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-electricity-office');
    }

    public function openElectricityOfficeForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'electricity-office-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'upazila_id', 'name', 'phone', 'address', 'map', 'status', 'photo', 'image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = ElectricityOffice::with('upazila', 'user')->where('status', 'active');
        if (filled($this->search)) {
            $s = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                    ->orWhere('address', 'like', $s)
                    ->orWhere('phone', 'like', $s);
            });
        }
        if (! empty($this->filter_upazila_id)) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        $electricityOffices = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322, 327])->orderBy('name')->get();

        return view('livewire.web.electricity.electricity-office-component', compact('electricityOffices', 'upazilas'))
            ->layout('components.layouts.web');
    }
}
