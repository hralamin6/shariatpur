<?php

namespace App\Livewire\Web\Work;

use App\Models\Upazila;
use App\Models\Work;
use Livewire\Component;
use Livewire\WithFileUploads;

class WorkComponent extends Component
{
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $upazila_id = null;
    public string $title = '';
    public string $institution_name = '';
    public string $designation = '';
    public int $posts_count = 0;
    public string $educational_qualification = '';
    public string $experience = '';
    public string $salary = '';
    public string $email = '';
    public string $phone = '';
    public string $last_date = '';
    public string $address = '';
    public string $application_link = '';
    public string $details = '';
    public string $status = 'active';

    public string $search = '';
    public ?int $filter_upazila_id = null;

    // Photo upload (URL or File)
    public $photo; // uploaded file
    public string $image_url = '';

    public ?int $detailsId = null;
    public array $workDetails = [];

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'title' => 'required|string|max:200',
            'institution_name' => 'nullable|string|max:200',
            'designation' => 'nullable|string|max:150',
            'posts_count' => 'nullable|integer|min:0|max:5000',
            'educational_qualification' => 'nullable|string|max:2000',
            'experience' => 'nullable|string|max:500',
            'salary' => 'nullable|string|max:200',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:30',
            'last_date' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'application_link' => 'nullable|url|max:500',
            'details' => 'nullable|string|max:4000',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(Work $work): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($work->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createWork(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $work = Work::create([
            'user_id' => auth()->id(),
            'upazila_id' => $this->upazila_id,
            'title' => $this->title,
            'institution_name' => $this->institution_name ?: null,
            'designation' => $this->designation ?: null,
            'posts_count' => $this->posts_count ?: 0,
            'educational_qualification' => $this->educational_qualification ?: null,
            'experience' => $this->experience ?: null,
            'salary' => $this->salary ?: null,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'last_date' => $this->last_date ?: null,
            'address' => $this->address ?: null,
            'application_link' => $this->application_link ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $work->addMediaFromUrl($this->image_url)->usingFileName($work->id. '.' . $extension)->toMediaCollection('work');
            $path = storage_path("app/public/Work/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo!=null) {
            $media = $work->addMedia($this->photo->getRealPath())->usingFileName($work->id. '.' . $this->photo->extension())->toMediaCollection('work');
            $path = storage_path("app/public/Work/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'work-form');
    }

    public function selectWorkForEdit(int $id): void
    {
        $work = Work::findOrFail($id);
        $this->authorizeOwnerOrAdmin($work);

        $this->selectedId = $work->id;
        $this->upazila_id = $work->upazila_id;
        $this->title = $work->title;
        $this->institution_name = $work->institution_name ?? '';
        $this->designation = $work->designation ?? '';
        $this->posts_count = (int)($work->posts_count ?? 0);
        $this->educational_qualification = $work->educational_qualification ?? '';
        $this->experience = $work->experience ?? '';
        $this->salary = $work->salary ?? '';
        $this->email = $work->email ?? '';
        $this->phone = $work->phone ?? '';
        $this->last_date = optional($work->last_date)->format('Y-m-d') ?? '';
        $this->address = $work->address ?? '';
        $this->application_link = $work->application_link ?? '';
        $this->details = $work->details ?? '';
        $this->status = $work->status ?? 'active';

        $this->dispatch('open-modal', 'work-form');
    }

    public function updateWork(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $work = Work::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($work);

        $work->update([
            'upazila_id' => $this->upazila_id,
            'title' => $this->title,
            'institution_name' => $this->institution_name ?: null,
            'designation' => $this->designation ?: null,
            'posts_count' => $this->posts_count ?: 0,
            'educational_qualification' => $this->educational_qualification ?: null,
            'experience' => $this->experience ?: null,
            'salary' => $this->salary ?: null,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'last_date' => $this->last_date ?: null,
            'address' => $this->address ?: null,
            'application_link' => $this->application_link ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $work->addMediaFromUrl($this->image_url)->usingFileName($work->id. '.' . $extension)->toMediaCollection('work');
            $path = storage_path("app/public/Work/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo!=null) {
            $media = $work->addMedia($this->photo->getRealPath())->usingFileName($work->id. '.' . $this->photo->extension())->toMediaCollection('work');
            $path = storage_path("app/public/Work/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'work-form');
    }

    public function confirmDelete(int $id): void
    {
        $work = Work::findOrFail($id);
        $this->authorizeOwnerOrAdmin($work);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-work');
    }

    public function deleteSelectedWork(): void
    {
        if (!$this->selectedId) return;
        $work = Work::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($work);
        $work->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-work');
    }

    public function openWorkForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'work-form');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'selectedId','upazila_id','title','institution_name','designation','posts_count','educational_qualification','experience','salary','email','phone','last_date','address','application_link','details','status','photo','image_url'
        ]);
        $this->status = 'active';
    }

    public function showDetails(int $id): void
    {
        $work = Work::with('upazila', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($work, 'getFirstMediaUrl')) {
            $photoUrl = $work->getFirstMediaUrl('work', 'avatar') ?: $work->getFirstMediaUrl('work');
        }

        $this->workDetails = [
            'title' => $work->title,
            'institution_name' => $work->institution_name,
            'designation' => $work->designation,
            'posts_count' => (int)($work->posts_count ?? 0),
            'educational_qualification' => $work->educational_qualification,
            'experience' => $work->experience,
            'salary' => $work->salary,
            'email' => $work->email,
            'phone' => $work->phone,
            'last_date' => $work->last_date,
            'address' => $work->address,
            'application_link' => $work->application_link,
            'upazila' => $work->upazila?->name,
            'details' => $work->details,
            'status' => $work->status,
            'photo_url' => $photoUrl,
            'created_by' => $work->user?->name,
            'created_at' => optional($work->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'work-details');
    }

    public function render()
    {
        $query = Work::with('upazila', 'user')->where('status', 'active');
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', $s)
                  ->orWhere('institution_name', 'like', $s)
                  ->orWhere('designation', 'like', $s)
                  ->orWhere('educational_qualification', 'like', $s)
                  ->orWhere('address', 'like', $s)
                  ->orWhere('details', 'like', $s);
            });
        }
        if (!empty($this->filter_upazila_id)) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        $works = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.work.work-component', compact('works', 'upazilas'))
            ->layout('components.layouts.web');
    }
}

