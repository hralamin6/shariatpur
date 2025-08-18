<?php

namespace App\Livewire\Web\Doctor;

use App\Models\Doctor;
use App\Models\DoctorCategory;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DoctorComponent extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    public ?int $selectedId = null;

    public ?int $doctor_category_id = null;
    public string $name = '';
    public string $qualification = '';
    public string $current_position = '';
    public string $chamber_one = '';
    public string $chamber_two = '';
    public string $chamber_three = '';
    public string $chamber_one_phone = '';
    public string $chamber_two_phone = '';
    public string $chamber_three_phone = '';
    public string $status = 'active';

    // Added properties
    public $photo, $image_url; // temporary uploaded file
    public string $search = '';

    public ?int $detailsId = null;
    public array $details = [];

    protected function rules(): array
    {
        return [
            'doctor_category_id' => 'required|exists:doctor_categories,id',
            'name' => 'required|string|max:150',
            'qualification' => 'required|string|max:255',
            'current_position' => 'required|string|max:255',
            'chamber_one' => 'required|string|max:255',
            'chamber_two' => 'nullable|string|max:255',
            'chamber_three' => 'nullable|string|max:255',
            'chamber_one_phone' => 'required|string|max:30',
            'chamber_two_phone' => 'nullable|string|max:30',
            'chamber_three_phone' => 'nullable|string|max:30',
            'status' => 'required|in:active,inactive',
            // Added validation for photo
            'photo' => 'nullable|image|max:2048',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(Doctor $doctor): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($doctor->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createDoctor(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('doctors', 'public');
        }

        $doctor = Doctor::create([
            'user_id' => auth()->id(),
            'doctor_category_id' => $this->doctor_category_id,
            'name' => $this->name,
            'qualification' => $this->qualification ?: null,
            'current_position' => $this->current_position ?: null,
            'chamber_one' => $this->chamber_one ?: null,
            'chamber_two' => $this->chamber_two ?: null,
            'chamber_three' => $this->chamber_three ?: null,
            'chamber_one_phone' => $this->chamber_one_phone ?: null,
            'chamber_two_phone' => $this->chamber_two_phone ?: null,
            'chamber_three_phone' => $this->chamber_three_phone ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $doctor->addMediaFromUrl($this->image_url)->usingFileName($doctor->id. '.' . $extension)->toMediaCollection('doctor');
            $path = storage_path("app/public/Doctor/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $doctor->addMedia($this->photo->getRealPath())->usingFileName($doctor->name. '.' . $this->photo->extension())->toMediaCollection('doctor');
            $path = storage_path("app/public/Doctor/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'create-doctor');
    }

    public function selectDoctorForEdit(int $id): void
    {
        $doctor = Doctor::with('category')->findOrFail($id);
        $this->authorizeOwnerOrAdmin($doctor);

        $this->selectedId = $doctor->id;
        $this->doctor_category_id = $doctor->doctor_category_id;
        $this->name = $doctor->name;
        $this->qualification = $doctor->qualification ?? '';
        $this->current_position = $doctor->current_position ?? '';
        $this->chamber_one = $doctor->chamber_one ?? '';
        $this->chamber_two = $doctor->chamber_two ?? '';
        $this->chamber_three = $doctor->chamber_three ?? '';
        $this->chamber_one_phone = $doctor->chamber_one_phone ?? '';
        $this->chamber_two_phone = $doctor->chamber_two_phone ?? '';
        $this->chamber_three_phone = $doctor->chamber_three_phone ?? '';
        $this->status = $doctor->status ?? 'active';

        $this->dispatch('open-modal', 'edit-doctor');
    }

    public function updateDoctor(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $doctor = Doctor::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($doctor);

        $update = [
            'doctor_category_id' => $this->doctor_category_id,
            'name' => $this->name,
            'qualification' => $this->qualification ?: null,
            'current_position' => $this->current_position ?: null,
            'chamber_one' => $this->chamber_one ?: null,
            'chamber_two' => $this->chamber_two ?: null,
            'chamber_three' => $this->chamber_three ?: null,
            'chamber_one_phone' => $this->chamber_one_phone ?: null,
            'chamber_two_phone' => $this->chamber_two_phone ?: null,
            'chamber_three_phone' => $this->chamber_three_phone ?: null,
            'status' => $this->status,
        ];

        $doctor->update($update);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $doctor->addMediaFromUrl($this->image_url)->usingFileName($doctor->id. '.' . $extension)->toMediaCollection('doctor');
            $path = storage_path("app/public/Doctor/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $doctor->addMedia($this->photo->getRealPath())->usingFileName($doctor->name. '.' . $this->photo->extension())->toMediaCollection('doctor');
            $path = storage_path("app/public/Doctor/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $this->alert('success', __('Doctor updated successfully.'));
        $this->dispatch('close-modal', 'edit-doctor');
    }

    public function confirmDelete(int $id): void
    {
        $doctor = Doctor::findOrFail($id);
        $this->authorizeOwnerOrAdmin($doctor);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-doctor');
    }

    public function deleteSelectedDoctor(): void
    {
        if (!$this->selectedId) return;
        $doctor = Doctor::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($doctor);
        $doctor->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-doctor');
    }

    public function showDetails(int $id): void
    {
        $doctor = Doctor::with('category', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($doctor, 'getFirstMediaUrl')) {
            $photoUrl = $doctor->getFirstMediaUrl('photo');
        }
        if (!$photoUrl && !empty($doctor->photo)) {
            $photoUrl = Storage::url($doctor->photo);
        }

        $this->details = [
            'name' => $doctor->name,
            'category' => $doctor->category?->name,
            'qualification' => $doctor->qualification,
            'current_position' => $doctor->current_position,
            'chamber_one' => $doctor->chamber_one,
            'chamber_one_phone' => $doctor->chamber_one_phone,
            'chamber_two' => $doctor->chamber_two,
            'chamber_two_phone' => $doctor->chamber_two_phone,
            'chamber_three' => $doctor->chamber_three,
            'chamber_three_phone' => $doctor->chamber_three_phone,
            'status' => $doctor->status,
            'photo_url' => $photoUrl,
            'created_by' => $doctor->user?->name,
        ];

        $this->dispatch('open-modal', 'doctor-details');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'selectedId', 'name', 'qualification', 'current_position', 'image_url', 'photo',
            'chamber_one', 'chamber_two', 'chamber_three', 'chamber_one_phone', 'chamber_two_phone', 'chamber_three_phone', 'status'
        ]);
        $this->status = 'active';
    }

    public function mount($cat_id = null): void
    {
        // make optional; do not break existing route
        if ($cat_id) {
            $this->doctor_category_id = $cat_id;
        }
    }

    public function render()
    {
        $query = Doctor::with('category', 'user')->where('doctor_category_id', $this->doctor_category_id)->where('status', 'active');
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('chamber_one', 'like', $s)
                  ->orWhere('chamber_two', 'like', $s)
                  ->orWhere('chamber_three', 'like', $s);
            });
        }
        $doctors = $query->latest()->get();
        $categories = DoctorCategory::where('status', 'active')->orderBy('name')->get();
        return view('livewire.web.doctor.doctor-component', compact('doctors', 'categories'))
            ->layout('components.layouts.web');
    }
}
