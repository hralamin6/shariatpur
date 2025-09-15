<?php

namespace App\Livewire\Web\Institution;

use App\Models\Institution;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class InstitutionComponent extends Component
{
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $filter_type_id = null;
    public ?int $filter_upazila_id = null;

    public ?int $institution_type_id = null;
    public ?int $upazila_id = null;
    public string $name = '';
    public ?string $established_at = null;
    public string $phone = '';
    public string $email = '';
    public string $website = '';
    public string $address = '';
    public string $map = '';
    public string $details = '';
    public string $status = 'active';

    public string $search = '';

    // Photo upload (URL or File)
    public $photo; // uploaded file
    public string $image_url = '';

    public ?int $detailsId = null;
    public array $institutionDetails = [];

    public ?int $deleteId = null;

    protected function rules(): array
    {
        return [
            'institution_type_id' => 'required|exists:institution_types,id',
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:200',
            'established_at' => 'nullable|date|before_or_equal:today',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'map' => 'nullable|url|max:1000',
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

    protected function authorizeOwnerOrAdmin(Institution $institution): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($institution->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function mount($type_id = null): void
    {
        $this->resetForm();
        if ($type_id) {
            $this->filter_type_id = (int) $type_id;
            $this->institution_type_id = (int) $type_id;
        }
    }

    public function render()
    {
        $upazilas = Upazila::whereBetween('id', [322, 327])->orderBy('name')->get();
        $types = \App\Models\InstitutionType::orderBy('name')->get();

        $query = Institution::query()->where('status', 'active')->with('upazila', 'user', 'type');
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('address', 'like', $s)
                  ->orWhere('details', 'like', $s)
                  ->orWhere('phone', 'like', $s);
            });
        }
        if (!empty($this->filter_upazila_id)) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        if (!empty($this->filter_type_id)) {
            $query->where('institution_type_id', $this->filter_type_id);
        }

        $institutions = $query->latest()->get();

        return view('livewire.web.institution.institution-component', [
            'institutions' => $institutions,
            'upazilas' => $upazilas,
            'types' => $types,
        ])->layout('components.layouts.web');
    }

    public function openInstitutionForm(): void
    {
        $this->resetForm();
        if ($this->filter_type_id) {
            $this->institution_type_id = $this->filter_type_id;
        }
        $this->dispatch('open-modal', 'institution-form');
    }

    public function createInstitution(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $institution = Institution::create([
            'user_id' => auth()->id(),
            'institution_type_id' => $this->institution_type_id,
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'established_at' => $this->established_at ?: null,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'website' => $this->website ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        // media handling (image_url first then file)
        if (!empty($this->image_url) && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $institution->addMediaFromUrl($this->image_url)->usingFileName($institution->id . '.' . $extension)->toMediaCollection('institution');
            $path = storage_path('app/public/Institution/' . $media->id . '/' . $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo) {
            $media = $institution->addMedia($this->photo->getRealPath())->usingFileName(($institution->name ?: 'institution') . '.' . $this->photo->extension())->toMediaCollection('institution');
            $path = storage_path('app/public/Institution/' . $media->id . '/' . $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        session()->flash('success', 'Institution created successfully.');
        $this->resetForm();
        $this->dispatch('close-modal', 'institution-form');
    }

    public function selectInstitutionForEdit(int $id): void
    {
        $institution = Institution::findOrFail($id);
        $this->authorizeOwnerOrAdmin($institution);

        $this->selectedId = $institution->id;
        $this->upazila_id = $institution->upazila_id;
        $this->name = $institution->name;
        $this->institution_type_id = $institution->institution_type_id;
        $this->established_at = $institution->established_at?->format('Y-m-d');
        $this->phone = $institution->phone ?? '';
        $this->email = $institution->email ?? '';
        $this->website = $institution->website ?? '';
        $this->address = $institution->address ?? '';
        $this->map = $institution->map ?? '';
        $this->details = $institution->details ?? '';
        $this->status = $institution->status ?? 'active';

        $this->dispatch('open-modal', 'institution-form');
    }

    public function updateInstitution(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $institution = Institution::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($institution);

        $institution->update([
            'institution_type_id' => $this->institution_type_id,
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'established_at' => $this->established_at ?: null,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'website' => $this->website ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if (!empty($this->image_url) && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $institution->addMediaFromUrl($this->image_url)->usingFileName($institution->id . '.' . $extension)->toMediaCollection('institution');
            $path = storage_path('app/public/Institution/' . $media->id . '/' . $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo) {
            $media = $institution->addMedia($this->photo->getRealPath())->usingFileName(($institution->name ?: 'institution') . '.' . $this->photo->extension())->toMediaCollection('institution');
            $path = storage_path('app/public/Institution/' . $media->id . '/' . $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        session()->flash('success', 'Institution updated successfully.');
        $this->dispatch('close-modal', 'institution-form');
    }

    public function confirmDelete(int $id): void
    {
        $institution = Institution::findOrFail($id);
        $this->authorizeOwnerOrAdmin($institution);
        $this->deleteId = $id;
        $this->dispatch('open-modal', 'delete-institution');
    }

    public function deleteInstitution(): void
    {
        if (!$this->deleteId) return;
        $institution = Institution::findOrFail($this->deleteId);
        $this->authorizeOwnerOrAdmin($institution);
        $institution->delete();
        $this->deleteId = null;
        session()->flash('success', 'Institution deleted successfully.');
        $this->dispatch('close-modal', 'delete-institution');
    }

    public function showDetails(int $id): void
    {
        $institution = Institution::with('upazila', 'user')->findOrFail($id);
        $photoUrl = null;
        if (method_exists($institution, 'getFirstMediaUrl')) {
            $photoUrl = $institution->getFirstMediaUrl('institution', 'avatar') ?: $institution->getFirstMediaUrl('institution');
        }

        $this->detailsId = $id;
        $this->institutionDetails = [
            'id' => $institution->id,
            'name' => $institution->name,
            'type' => $institution->type,
            'established_at' => $institution->established_at?->format('d M Y'),
            'phone' => $institution->phone,
            'email' => $institution->email,
            'website' => $institution->website,
            'address' => $institution->address,
            'map' => $institution->map,
            'details' => $institution->details,
            'status' => $institution->status,
            'photo_url' => $photoUrl,
            'created_by' => $institution->user?->name,
            'created_at' => optional($institution->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'institution-details');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'selectedId', 'filter_type_id', 'institution_type_id', 'filter_upazila_id', 'upazila_id', 'name', 'established_at', 'phone',
            'email', 'website', 'address', 'map', 'details', 'status', 'photo', 'image_url'
        ]);
        $this->status = 'active';
    }
}
