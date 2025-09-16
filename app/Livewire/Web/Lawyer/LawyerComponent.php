<?php

namespace App\Livewire\Web\Lawyer;

use App\Models\Lawyer;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class LawyerComponent extends Component
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
    public array $lawyerDetails = [];

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

    protected function authorizeOwnerOrAdmin(Lawyer $lawyer): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($lawyer->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createLawyer(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $lawyer = Lawyer::create([
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
            $media =  $lawyer->addMediaFromUrl($this->image_url)->usingFileName($lawyer->id. '.' . $extension)->toMediaCollection('lawyer');
            $path = storage_path('app/public/Lawyer/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo) {
            $media = $lawyer->addMedia($this->photo->getRealPath())->usingFileName(($this->name ?: 'lawyer'). '.' . $this->photo->extension())->toMediaCollection('lawyer');
            $path = storage_path('app/public/Lawyer/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'lawyer-form');
    }

    public function selectLawyerForEdit(int $id): void
    {
        $lawyer = Lawyer::findOrFail($id);
        $this->authorizeOwnerOrAdmin($lawyer);

        $this->selectedId = $lawyer->id;
        $this->upazila_id = $lawyer->upazila_id;
        $this->name = $lawyer->name;
        $this->designation = $lawyer->designation ?? '';
        $this->thana = $lawyer->thana ?? '';
        $this->address = $lawyer->address ?? '';
        $this->map = $lawyer->map ?? '';
        $this->phone = $lawyer->phone ?? '';
        $this->alt_phone = $lawyer->alt_phone ?? '';
        $this->details = $lawyer->details ?? '';
        $this->status = $lawyer->status ?? 'active';

        $this->dispatch('open-modal', 'lawyer-form');
    }

    public function showDetails(int $id): void
    {
        $lawyer = Lawyer::with('upazila', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($lawyer, 'getFirstMediaUrl')) {
            $photoUrl = $lawyer->getFirstMediaUrl('lawyer', 'avatar') ?: $lawyer->getFirstMediaUrl('lawyer');
        }

        $this->lawyerDetails = [
            'name' => $lawyer->name,
            'designation' => $lawyer->designation,
            'thana' => $lawyer->thana,
            'upazila' => $lawyer->upazila?->name,
            'address' => $lawyer->address,
            'map' => $lawyer->map,
            'phone' => $lawyer->phone,
            'alt_phone' => $lawyer->alt_phone,
            'details' => $lawyer->details,
            'status' => $lawyer->status,
            'photo_url' => $photoUrl,
            'created_by' => $lawyer->user?->name,
            'created_at' => optional($lawyer->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'lawyer-details');
    }

    public function updateLawyer(): void
    {
        if (!$this->selectedId) {
            return;
        }
        $this->validate();

        $lawyer = Lawyer::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($lawyer);

        $lawyer->update([
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
            $media =  $lawyer->addMediaFromUrl($this->image_url)->usingFileName($lawyer->id. '.' . $extension)->toMediaCollection('lawyer');
            $path = storage_path('app/public/Lawyer/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo) {
            $media = $lawyer->addMedia($this->photo->getRealPath())->usingFileName(($this->name ?: 'lawyer'). '.' . $this->photo->extension())->toMediaCollection('lawyer');
            $path = storage_path('app/public/Lawyer/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'lawyer-form');
    }

    public function confirmDelete(int $id): void
    {
        $lawyer = Lawyer::findOrFail($id);
        $this->authorizeOwnerOrAdmin($lawyer);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-lawyer');
    }

    public function deleteSelectedLawyer(): void
    {
        if (!$this->selectedId) {
            return;
        }
        $lawyer = Lawyer::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($lawyer);
        $lawyer->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-lawyer');
    }

    public function openLawyerForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'lawyer-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId','upazila_id','name','designation','thana','address','map','phone','alt_phone','details','status','photo','image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = Lawyer::with('upazila', 'user')->where('status', 'active');
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
        $lawyers = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.lawyer.lawyer-component', [
            'lawyers' => $lawyers,
            'upazilas' => $upazilas,
        ])->layout('components.layouts.web');
    }
}

