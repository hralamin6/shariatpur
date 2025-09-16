<?php

namespace App\Livewire\Web\Tutor;

use App\Models\Tutor;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class TutorComponent extends Component
{
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $upazila_id = null;
    public string $title = '';
    public string $type = 'tutor'; // tutor or tuition
    public string $class = '';
    public string $gender = '';
    public string $subject = '';
    public ?int $days_per_week = null;
    public ?int $salary = null;
    public string $address = '';
    public string $phone = '';
    public string $map = '';
    public string $details = '';
    public string $status = 'active';

    public string $search = '';
    public ?int $filter_upazila_id = null;
    public ?string $filter_type = null;

    public $photo; // uploaded file
    public string $image_url = '';

    public ?int $detailsId = null;
    public array $tutorDetails = [];

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'title' => 'required|string|max:180',
            'type' => 'required|in:tutor,tuition',
            'class' => 'nullable|string|max:120',
            'gender' => 'nullable|in:male,female,other',
            'subject' => 'nullable|string|max:200',
            'days_per_week' => 'nullable|integer|min:1|max:7',
            'salary' => 'nullable|integer|min:0',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:30',
            'map' => 'nullable|string|max:1000',
            'details' => 'nullable|string|max:2000',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(Tutor $tutor): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($tutor->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createTutor(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $tutor = Tutor::create([
            'user_id' => auth()->id(),
            'upazila_id' => $this->upazila_id,
            'title' => $this->title,
            'type' => $this->type,
            'class' => $this->class ?: null,
            'gender' => $this->gender ?: null,
            'subject' => $this->subject ?: null,
            'days_per_week' => $this->days_per_week,
            'salary' => $this->salary,
            'address' => $this->address ?: null,
            'phone' => $this->phone ?: null,
            'map' => $this->map ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $tutor->addMediaFromUrl($this->image_url)->usingFileName($tutor->id. '.' . $extension)->toMediaCollection('tutor');
            $path = storage_path("app/public/Tutor/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo!=null) {
            $media = $tutor->addMedia($this->photo->getRealPath())->usingFileName(($this->title ?: $tutor->id).'.' . $this->photo->extension())->toMediaCollection('tutor');
            $path = storage_path("app/public/Tutor/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'tutor-form');
    }

    public function selectTutorForEdit(int $id): void
    {
        $tutor = Tutor::findOrFail($id);
        $this->authorizeOwnerOrAdmin($tutor);

        $this->selectedId = $tutor->id;
        $this->upazila_id = $tutor->upazila_id;
        $this->title = $tutor->title;
        $this->type = $tutor->type;
        $this->class = $tutor->class ?? '';
        $this->gender = $tutor->gender ?? '';
        $this->subject = $tutor->subject ?? '';
        $this->days_per_week = $tutor->days_per_week;
        $this->salary = $tutor->salary;
        $this->address = $tutor->address ?? '';
        $this->phone = $tutor->phone ?? '';
        $this->map = $tutor->map ?? '';
        $this->details = $tutor->details ?? '';
        $this->status = $tutor->status ?? 'active';

        $this->dispatch('open-modal', 'tutor-form');
    }

    public function showDetails(int $id): void
    {
        $tutor = Tutor::with('upazila', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($tutor, 'getFirstMediaUrl')) {
            $photoUrl = $tutor->getFirstMediaUrl('tutor', 'avatar') ?: $tutor->getFirstMediaUrl('tutor');
        }

        $this->tutorDetails = [
            'title' => $tutor->title,
            'type' => $tutor->type,
            'class' => $tutor->class,
            'gender' => $tutor->gender,
            'subject' => $tutor->subject,
            'days_per_week' => $tutor->days_per_week,
            'salary' => $tutor->salary,
            'upazila' => $tutor->upazila?->name,
            'address' => $tutor->address,
            'map' => $tutor->map,
            'phone' => $tutor->phone,
            'details' => $tutor->details,
            'status' => $tutor->status,
            'photo_url' => $photoUrl,
            'created_by' => $tutor->user?->name,
            'created_at' => optional($tutor->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'tutor-details');
    }

    public function updateTutor(): void
    {
        if (!$this->selectedId) { return; }
        $this->validate();

        $tutor = Tutor::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($tutor);

        $tutor->update([
            'upazila_id' => $this->upazila_id,
            'title' => $this->title,
            'type' => $this->type,
            'class' => $this->class ?: null,
            'gender' => $this->gender ?: null,
            'subject' => $this->subject ?: null,
            'days_per_week' => $this->days_per_week,
            'salary' => $this->salary,
            'address' => $this->address ?: null,
            'phone' => $this->phone ?: null,
            'map' => $this->map ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $tutor->addMediaFromUrl($this->image_url)->usingFileName($tutor->id. '.' . $extension)->toMediaCollection('tutor');
            $path = storage_path("app/public/Tutor/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo!=null) {
            $media = $tutor->addMedia($this->photo->getRealPath())->usingFileName(($this->title ?: $tutor->id).'.' . $this->photo->extension())->toMediaCollection('tutor');
            $path = storage_path("app/public/Tutor/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'tutor-form');
    }

    public function confirmDelete(int $id): void
    {
        $tutor = Tutor::findOrFail($id);
        $this->authorizeOwnerOrAdmin($tutor);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-tutor');
    }

    public function deleteSelectedTutor(): void
    {
        if (!$this->selectedId) { return; }
        $tutor = Tutor::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($tutor);
        $tutor->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-tutor');
    }

    public function openTutorForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'tutor-form');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'selectedId','upazila_id','title','type','class','gender','subject','days_per_week','salary','address','phone','map','details','status','photo','image_url'
        ]);
        $this->status = 'active';
        $this->type = 'tutor';
    }

    public function render()
    {
        $query = Tutor::with('upazila', 'user')->where('status', 'active');
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', $s)
                  ->orWhere('subject', 'like', $s)
                  ->orWhere('class', 'like', $s)
                  ->orWhere('address', 'like', $s)
                  ->orWhere('phone', 'like', $s)
                  ->orWhere('details', 'like', $s);
            });
        }
        if (!empty($this->filter_upazila_id)) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        if (!empty($this->filter_type)) {
            $query->where('type', $this->filter_type);
        }
        $tutors = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.tutor.tutor-component', compact('tutors', 'upazilas'))
            ->layout('components.layouts.web');
    }
}

