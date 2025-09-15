<?php

namespace App\Livewire\Web\Police;

use App\Models\Police;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class PoliceComponent extends Component
{
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $upazila_id = null;
    public string $name = '';
    public string $designation = '';
    public string $thana = '';
    public string $address = '';
    public string $map = '';
    public string $phone = '';
    public string $alt_phone = '';
    public string $details = '';
    public string $status = 'active';

    public string $search = '';
    public ?int $filter_upazila_id = null;

    // Photo upload (URL or File)
    public $photo; // uploaded file
    public string $image_url = '';

    public ?int $detailsId = null;
    public array $policeDetails = [];

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150',
            'designation' => 'nullable|string|max:150',
            'thana' => 'nullable|string|max:150',
            'address' => 'nullable|string|max:500',
            'map' => 'nullable|url|max:1000',
            'phone' => 'nullable|string|max:30',
            'alt_phone' => 'nullable|string|max:30',
            'details' => 'nullable|string|max:2000',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url|max:1000',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(Police $police): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($police->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createPolice(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $police = Police::create([
            'user_id' => auth()->id(),
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'designation' => $this->designation ?: null,
            'thana' => $this->thana ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'phone' => $this->phone ?: null,
            'alt_phone' => $this->alt_phone ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if (!empty($this->image_url) && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $police->addMediaFromUrl($this->image_url)->usingFileName($police->id. '.' . $extension)->toMediaCollection('police');
            $path = storage_path('app/public/Police/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo) {
            $media = $police->addMedia($this->photo->getRealPath())->usingFileName(($this->name ?: 'police'). '.' . $this->photo->extension())->toMediaCollection('police');
            $path = storage_path('app/public/Police/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'police-form');
    }

    public function selectPoliceForEdit(int $id): void
    {
        $police = Police::findOrFail($id);
        $this->authorizeOwnerOrAdmin($police);

        $this->selectedId = $police->id;
        $this->upazila_id = $police->upazila_id;
        $this->name = $police->name;
        $this->designation = $police->designation ?? '';
        $this->thana = $police->thana ?? '';
        $this->address = $police->address ?? '';
        $this->map = $police->map ?? '';
        $this->phone = $police->phone ?? '';
        $this->alt_phone = $police->alt_phone ?? '';
        $this->details = $police->details ?? '';
        $this->status = $police->status ?? 'active';

        $this->dispatch('open-modal', 'police-form');
    }

    public function showDetails(int $id): void
    {
        $police = Police::with('upazila', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($police, 'getFirstMediaUrl')) {
            $photoUrl = $police->getFirstMediaUrl('police', 'avatar') ?: $police->getFirstMediaUrl('police');
        }

        $this->policeDetails = [
            'name' => $police->name,
            'designation' => $police->designation,
            'thana' => $police->thana,
            'upazila' => $police->upazila?->name,
            'address' => $police->address,
            'map' => $police->map,
            'phone' => $police->phone,
            'alt_phone' => $police->alt_phone,
            'details' => $police->details,
            'status' => $police->status,
            'photo_url' => $photoUrl,
            'created_by' => $police->user?->name,
            'created_at' => optional($police->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'police-details');
    }

    public function updatePolice(): void
    {
        if (!$this->selectedId) {
            return;
        }
        $this->validate();

        $police = Police::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($police);

        $police->update([
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'designation' => $this->designation ?: null,
            'thana' => $this->thana ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'phone' => $this->phone ?: null,
            'alt_phone' => $this->alt_phone ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if (!empty($this->image_url) && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $police->addMediaFromUrl($this->image_url)->usingFileName($police->id. '.' . $extension)->toMediaCollection('police');
            $path = storage_path('app/public/Police/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo) {
            $media = $police->addMedia($this->photo->getRealPath())->usingFileName(($this->name ?: 'police'). '.' . $this->photo->extension())->toMediaCollection('police');
            $path = storage_path('app/public/Police/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'police-form');
    }

    public function confirmDelete(int $id): void
    {
        $police = Police::findOrFail($id);
        $this->authorizeOwnerOrAdmin($police);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-police');
    }

    public function deleteSelectedPolice(): void
    {
        if (!$this->selectedId) {
            return;
        }
        $police = Police::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($police);
        $police->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-police');
    }

    public function openPoliceForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'police-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId','upazila_id','name','designation','thana','address','map','phone','alt_phone','details','status','photo','image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = Police::with('upazila', 'user')->where('status', 'active');
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('designation', 'like', $s)
                  ->orWhere('thana', 'like', $s)
                  ->orWhere('address', 'like', $s)
                  ->orWhere('phone', 'like', $s)
                  ->orWhere('details', 'like', $s);
            });
        }
        if (!empty($this->filter_upazila_id)) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        $policeList = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.police.police-component', [
            'policeList' => $policeList,
            'upazilas' => $upazilas,
        ])->layout('components.layouts.web');
    }
}
