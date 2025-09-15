<?php

namespace App\Livewire\Web\CourierService;

use App\Models\CourierService;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class CourierServiceComponent extends Component
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

    public $photo; // uploaded file

    public string $image_url = '';

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150|unique:courier_services,name,'.($this->selectedId ?? 'NULL').',id',
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

    protected function authorizeOwnerOrAdmin(CourierService $cs): void
    {
        if (! auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($cs->user_id !== auth()->id() && ! $this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createCourierService(): void
    {
        if (! auth()->check()) {
            redirect()->route('login')->send();

            return;
        }
        $this->validate();

        $cs = CourierService::create([
            'user_id' => auth()->id(),
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url != null && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $cs->addMediaFromUrl($this->image_url)->usingFileName($cs->id.'.'.$extension)->toMediaCollection('courierservice');
            $path = storage_path('app/public/Courierservice/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }

        } elseif ($this->photo != null) {
            $media = $cs->addMedia($this->photo->getRealPath())->usingFileName($cs->name.'.'.$this->photo->extension())->toMediaCollection('courierservice');
            $path = storage_path('app/public/Courierservice/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'courier-service-form');
    }

    public function selectCourierServiceForEdit(int $id): void
    {
        $cs = CourierService::findOrFail($id);
        $this->authorizeOwnerOrAdmin($cs);

        $this->selectedId = $cs->id;
        $this->upazila_id = $cs->upazila_id;
        $this->name = $cs->name;
        $this->phone = $cs->phone ?? '';
        $this->address = $cs->address ?? '';
        $this->map = $cs->map ?? '';
        $this->status = $cs->status ?? 'active';

        $this->dispatch('open-modal', 'courier-service-form');
    }

    public function updateCourierService(): void
    {
        if (! $this->selectedId) {
            return;
        }
        $this->validate();

        $cs = CourierService::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($cs);

        $cs->update([
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url != null && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $cs->addMediaFromUrl($this->image_url)->usingFileName($cs->id.'.'.$extension)->toMediaCollection('courierservice');
            $path = storage_path('app/public/Courierservice/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }

        } elseif ($this->photo != null) {
            $media = $cs->addMedia($this->photo->getRealPath())->usingFileName($cs->name.'.'.$this->photo->extension())->toMediaCollection('courierservice');
            $path = storage_path('app/public/Courierservice/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'courier-service-form');
    }

    public function confirmDelete(int $id): void
    {
        $cs = CourierService::findOrFail($id);
        $this->authorizeOwnerOrAdmin($cs);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-courier-service');
    }

    public function deleteSelectedCourierService(): void
    {
        if (! $this->selectedId) {
            return;
        }
        $cs = CourierService::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($cs);
        $cs->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-courier-service');
    }

    public function openCourierServiceForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'courier-service-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'upazila_id', 'name', 'phone', 'address', 'map', 'status', 'photo', 'image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = CourierService::with('upazila', 'user')->where('status', 'active');
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
        $courierServices = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322, 327])->orderBy('name')->get();

        return view('livewire.web.courierservice.courier-service-component', compact('courierServices', 'upazilas'))
            ->layout('components.layouts.web');
    }
}
