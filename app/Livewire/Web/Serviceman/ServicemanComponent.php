<?php

namespace App\Livewire\Web\Serviceman;

use App\Models\Serviceman;
use App\Models\ServicemanType;
use App\Models\Upazila;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class ServicemanComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $serviceman_type_id = null;
    public ?int $upazila_id = null;
    public string $name = '';
    public string $service_title = '';
    public int $experience_years = 0;
    public string $phone = '';
    public string $address = '';
    public string $details = '';
    public string $status = 'active';

    public string $search = '';
    public ?int $filter_type_id = null;
    public ?int $filter_upazila_id = null;

    public $photo; // uploaded file
    public string $image_url = '';

    public ?int $detailsId = null;
    public array $servicemanDetails = [];

    protected function rules(): array
    {
        return [
            'serviceman_type_id' => 'required|exists:serviceman_types,id',
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150',
            'service_title' => 'nullable|string|max:150',
            'experience_years' => 'nullable|integer|min:0|max:60',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'details' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:4096',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(Serviceman $serviceman): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($serviceman->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createServiceman(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $serviceman = Serviceman::create([
            'user_id' => auth()->id(),
            'serviceman_type_id' => $this->serviceman_type_id,
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'service_title' => $this->service_title ?: null,
            'experience_years' => $this->experience_years ?: 0,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $serviceman->addMediaFromUrl($this->image_url)->usingFileName($serviceman->id. '.' . $extension)->toMediaCollection('serviceman');
            $path = storage_path("app/public/Serviceman/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo!=null) {
            $media = $serviceman->addMedia($this->photo->getRealPath())->usingFileName($serviceman->name. '.' . $this->photo->extension())->toMediaCollection('serviceman');
            $path = storage_path("app/public/Serviceman/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'serviceman-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectServicemanForEdit(int $id): void
    {
        $serviceman = Serviceman::findOrFail($id);
        $this->authorizeOwnerOrAdmin($serviceman);

        $this->selectedId = $serviceman->id;
        $this->serviceman_type_id = $serviceman->serviceman_type_id;
        $this->upazila_id = $serviceman->upazila_id;
        $this->name = $serviceman->name;
        $this->service_title = $serviceman->service_title ?? '';
        $this->experience_years = (int) ($serviceman->experience_years ?? 0);
        $this->phone = $serviceman->phone ?? '';
        $this->address = $serviceman->address ?? '';
        $this->details = $serviceman->details ?? '';
        $this->status = $serviceman->status ?? 'active';

        $this->dispatch('open-modal', 'serviceman-form');
    }

    public function updateServiceman(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $serviceman = Serviceman::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($serviceman);

        $serviceman->update([
            'serviceman_type_id' => $this->serviceman_type_id,
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'service_title' => $this->service_title ?: null,
            'experience_years' => $this->experience_years ?: 0,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $serviceman->addMediaFromUrl($this->image_url)->usingFileName($serviceman->id. '.' . $extension)->toMediaCollection('serviceman');
            $path = storage_path("app/public/Serviceman/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo!=null) {
            $media = $serviceman->addMedia($this->photo->getRealPath())->usingFileName($serviceman->name. '.' . $this->photo->extension())->toMediaCollection('serviceman');
            $path = storage_path("app/public/Serviceman/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'serviceman-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $serviceman = Serviceman::findOrFail($id);
        $this->authorizeOwnerOrAdmin($serviceman);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-serviceman');
    }

    public function deleteSelectedServiceman(): void
    {
        if (!$this->selectedId) return;
        $serviceman = Serviceman::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($serviceman);
        $serviceman->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-serviceman');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function openServicemanForm(): void
    {
        $this->resetForm();
        if ($this->filter_type_id) {
            $this->serviceman_type_id = $this->filter_type_id;
        }
        $this->dispatch('open-modal', 'serviceman-form');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'selectedId','serviceman_type_id','upazila_id','name','service_title','experience_years','phone','address','details','status','photo','image_url'
        ]);
        $this->status = 'active';
    }

    public function mount($type_id = null): void
    {
        if ($type_id) {
            $this->filter_type_id = (int) $type_id;
            $this->serviceman_type_id = (int) $type_id;
        }
    }

    public function render()
    {
        $query = Serviceman::with('type','upazila','user')->where('status','active');
        if ($this->filter_type_id) {
            $query->where('serviceman_type_id', $this->filter_type_id);
        }
        if ($this->filter_upazila_id) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function($q) use($s) {
                $q->where('name','like',$s)
                  ->orWhere('service_title','like',$s)
                  ->orWhere('phone','like',$s)
                  ->orWhere('details','like',$s)
                  ->orWhere('address','like',$s);
            });
        }
        $servicemen = $query->latest()->get();
        $types = ServicemanType::orderBy('name')->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.serviceman.serviceman-component', compact('servicemen','types','upazilas'))
            ->layout('components.layouts.web');
    }

    public function showDetails(int $id): void
    {
        $serviceman = Serviceman::with('type','upazila','user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($serviceman, 'getFirstMediaUrl')) {
            $photoUrl = $serviceman->getFirstMediaUrl('serviceman', 'avatar') ?: $serviceman->getFirstMediaUrl('serviceman');
        }

        $this->servicemanDetails = [
            'name' => $serviceman->name,
            'type' => $serviceman->type?->name,
            'upazila' => $serviceman->upazila?->name,
            'service_title' => $serviceman->service_title,
            'experience_years' => (int)($serviceman->experience_years ?? 0),
            'address' => $serviceman->address,
            'phone' => $serviceman->phone,
            'details' => $serviceman->details,
            'status' => $serviceman->status,
            'photo_url' => $photoUrl,
            'created_by' => $serviceman->user?->name,
            'created_at' => optional($serviceman->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'serviceman-details');
    }
}

