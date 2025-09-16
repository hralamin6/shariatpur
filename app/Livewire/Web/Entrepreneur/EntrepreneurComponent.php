<?php

namespace App\Livewire\Web\Entrepreneur;

use App\Models\Entrepreneur;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class EntrepreneurComponent extends Component
{
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $upazila_id = null;

    public string $name = '';

    public string $service = '';

    public string $facebook_page = '';

    public string $phone = '';

    public string $address = '';

    public string $details = '';

    public string $status = 'active';

    public string $search = '';

    public ?int $filter_upazila_id = null;

    // Photo upload (URL or File)
    public $photo; // uploaded file

    public string $image_url = '';

    public ?int $detailsId = null;

    public array $entrepreneurDetails = [];

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150|unique:entrepreneurs,name,'.($this->selectedId ?? 'NULL').',id',
            'service' => 'nullable|string|max:150',
            'facebook_page' => 'nullable|url|max:500',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
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

    protected function authorizeOwnerOrAdmin(Entrepreneur $entrepreneur): void
    {
        if (! auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($entrepreneur->user_id !== auth()->id() && ! $this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createEntrepreneur(): void
    {
        if (! auth()->check()) {
            redirect()->route('login')->send();

            return;
        }
        $this->validate();

        $entrepreneur = Entrepreneur::create([
            'user_id' => auth()->id(),
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'service' => $this->service ?: null,
            'facebook_page' => $this->facebook_page ?: null,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url != null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $entrepreneur->addMediaFromUrl($this->image_url)->usingFileName($entrepreneur->id.'.'.$extension)->toMediaCollection('entrepreneur');
            $path = storage_path('app/public/Entrepreneur/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo != null) {
            $media = $entrepreneur->addMedia($this->photo->getRealPath())->usingFileName($entrepreneur->name.'.'.$this->photo->extension())->toMediaCollection('entrepreneur');
            $path = storage_path('app/public/Entrepreneur/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'entrepreneur-form');
    }

    public function selectEntrepreneurForEdit(int $id): void
    {
        $entrepreneur = Entrepreneur::findOrFail($id);
        $this->authorizeOwnerOrAdmin($entrepreneur);

        $this->selectedId = $entrepreneur->id;
        $this->upazila_id = $entrepreneur->upazila_id;
        $this->name = $entrepreneur->name;
        $this->service = $entrepreneur->service ?? '';
        $this->facebook_page = $entrepreneur->facebook_page ?? '';
        $this->phone = $entrepreneur->phone ?? '';
        $this->address = $entrepreneur->address ?? '';
        $this->details = $entrepreneur->details ?? '';
        $this->status = $entrepreneur->status ?? 'active';

        $this->dispatch('open-modal', 'entrepreneur-form');
    }

    public function showDetails(int $id): void
    {
        $entrepreneur = Entrepreneur::with('upazila', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($entrepreneur, 'getFirstMediaUrl')) {
            $photoUrl = $entrepreneur->getFirstMediaUrl('entrepreneur', 'avatar') ?: $entrepreneur->getFirstMediaUrl('entrepreneur');
        }

        $this->entrepreneurDetails = [
            'name' => $entrepreneur->name,
            'upazila' => $entrepreneur->upazila?->name,
            'service' => $entrepreneur->service,
            'facebook_page' => $entrepreneur->facebook_page,
            'address' => $entrepreneur->address,
            'phone' => $entrepreneur->phone,
            'details' => $entrepreneur->details,
            'status' => $entrepreneur->status,
            'photo_url' => $photoUrl,
            'created_by' => $entrepreneur->user?->name,
            'created_at' => optional($entrepreneur->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'entrepreneur-details');
    }

    public function updateEntrepreneur(): void
    {
        if (! $this->selectedId) {
            return;
        }
        $this->validate();

        $entrepreneur = Entrepreneur::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($entrepreneur);

        $entrepreneur->update([
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'service' => $this->service ?: null,
            'facebook_page' => $this->facebook_page ?: null,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url != null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $entrepreneur->addMediaFromUrl($this->image_url)->usingFileName($entrepreneur->id.'.'.$extension)->toMediaCollection('entrepreneur');
            $path = storage_path('app/public/Entrepreneur/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo != null) {
            $media = $entrepreneur->addMedia($this->photo->getRealPath())->usingFileName($entrepreneur->name.'.'.$this->photo->extension())->toMediaCollection('entrepreneur');
            $path = storage_path('app/public/Entrepreneur/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'entrepreneur-form');
    }

    public function confirmDelete(int $id): void
    {
        $entrepreneur = Entrepreneur::findOrFail($id);
        $this->authorizeOwnerOrAdmin($entrepreneur);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-entrepreneur');
    }

    public function deleteSelectedEntrepreneur(): void
    {
        if (! $this->selectedId) {
            return;
        }
        $entrepreneur = Entrepreneur::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($entrepreneur);
        $entrepreneur->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-entrepreneur');
    }

    public function openEntrepreneurForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'entrepreneur-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'upazila_id', 'name', 'service', 'facebook_page', 'phone', 'address', 'details', 'status', 'photo', 'image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = Entrepreneur::with('upazila', 'user')->where('status', 'active');
        if (filled($this->search)) {
            $s = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                    ->orWhere('service', 'like', $s)
                    ->orWhere('address', 'like', $s)
                    ->orWhere('phone', 'like', $s)
                    ->orWhere('details', 'like', $s);
            });
        }
        if (! empty($this->filter_upazila_id)) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        $entrepreneurs = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322, 327])->orderBy('name')->get();

        return view('livewire.web.entrepreneur.entrepreneur-component', compact('entrepreneurs', 'upazilas'))
            ->layout('components.layouts.web');
    }
}
