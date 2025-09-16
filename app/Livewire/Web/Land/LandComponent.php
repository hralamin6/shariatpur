<?php

namespace App\Livewire\Web\Land;

use App\Models\Land;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class LandComponent extends Component
{
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $upazila_id = null;
    public string $title = '';
    public string $area = '';
    public ?int $price = null;
    public string $phone = '';
    public string $address = '';
    public string $map = '';
    public string $details = '';
    public string $status = 'active';

    public string $search = '';
    public ?int $filter_upazila_id = null;

    public $photo; // uploaded file
    public string $image_url = '';

    public ?int $detailsId = null;
    public array $landDetails = [];

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'title' => 'required|string|max:180',
            'area' => 'nullable|string|max:120',
            'price' => 'nullable|integer|min:0',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'map' => 'nullable|string|max:1000',
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

    protected function authorizeOwnerOrAdmin(Land $land): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($land->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createLand(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $land = Land::create([
            'user_id' => auth()->id(),
            'upazila_id' => $this->upazila_id,
            'title' => $this->title,
            'area' => $this->area ?: null,
            'price' => $this->price,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if (!empty($this->image_url) && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $land->addMediaFromUrl($this->image_url)->usingFileName($land->id. '.' . $extension)->toMediaCollection('land');
            $path = storage_path('app/public/Land/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) { unlink($path); }
        } elseif ($this->photo) {
            $media = $land->addMedia($this->photo->getRealPath())->usingFileName(($this->title ?: 'land'). '.' . $this->photo->extension())->toMediaCollection('land');
            $path = storage_path('app/public/Land/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) { unlink($path); }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'land-form');
    }

    public function selectLandForEdit(int $id): void
    {
        $land = Land::findOrFail($id);
        $this->authorizeOwnerOrAdmin($land);

        $this->selectedId = $land->id;
        $this->upazila_id = $land->upazila_id;
        $this->title = $land->title;
        $this->area = $land->area ?? '';
        $this->price = $land->price;
        $this->phone = $land->phone ?? '';
        $this->address = $land->address ?? '';
        $this->map = $land->map ?? '';
        $this->details = $land->details ?? '';
        $this->status = $land->status ?? 'active';

        $this->dispatch('open-modal', 'land-form');
    }

    public function showDetails(int $id): void
    {
        $land = Land::with('upazila', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($land, 'getFirstMediaUrl')) {
            $photoUrl = $land->getFirstMediaUrl('land', 'avatar') ?: $land->getFirstMediaUrl('land');
        }

        $this->landDetails = [
            'title' => $land->title,
            'area' => $land->area,
            'price' => $land->price,
            'upazila' => $land->upazila?->name,
            'address' => $land->address,
            'map' => $land->map,
            'phone' => $land->phone,
            'details' => $land->details,
            'status' => $land->status,
            'photo_url' => $photoUrl,
            'created_by' => $land->user?->name,
            'created_at' => optional($land->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'land-details');
    }

    public function updateLand(): void
    {
        if (!$this->selectedId) { return; }
        $this->validate();

        $land = Land::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($land);

        $land->update([
            'upazila_id' => $this->upazila_id,
            'title' => $this->title,
            'area' => $this->area ?: null,
            'price' => $this->price,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'details' => $this->details ?: null,
            'status' => $this->status,
        ]);

        if (!empty($this->image_url) && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $land->addMediaFromUrl($this->image_url)->usingFileName($land->id. '.' . $extension)->toMediaCollection('land');
            $path = storage_path('app/public/Land/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) { unlink($path); }
        } elseif ($this->photo) {
            $media = $land->addMedia($this->photo->getRealPath())->usingFileName(($this->title ?: 'land'). '.' . $this->photo->extension())->toMediaCollection('land');
            $path = storage_path('app/public/Land/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) { unlink($path); }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'land-form');
    }

    public function confirmDelete(int $id): void
    {
        $land = Land::findOrFail($id);
        $this->authorizeOwnerOrAdmin($land);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-land');
    }

    public function deleteSelectedLand(): void
    {
        if (!$this->selectedId) { return; }
        $land = Land::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($land);
        $land->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-land');
    }

    public function openLandForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'land-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId','upazila_id','title','area','price','phone','address','map','details','status','photo','image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = Land::with('upazila', 'user')->where('status', 'active');
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', $s)
                  ->orWhere('address', 'like', $s)
                  ->orWhere('phone', 'like', $s)
                  ->orWhere('area', 'like', $s)
                  ->orWhere('details', 'like', $s);
            });
        }
        if (!empty($this->filter_upazila_id)) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        $lands = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322,327])->orderBy('name')->get();

        return view('livewire.web.land.land-component', compact('lands', 'upazilas'))
            ->layout('components.layouts.web');
    }
}

