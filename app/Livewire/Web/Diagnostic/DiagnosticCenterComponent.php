<?php

namespace App\Livewire\Web\Diagnostic;

use App\Models\DiagnosticCenter;
use App\Models\Upazila;
use Livewire\Component;
use Livewire\WithFileUploads;

class DiagnosticCenterComponent extends Component
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
            'name' => 'required|string|max:150|unique:diagnostic_centers,name,'.($this->selectedId ?? 'NULL').',id',
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

    protected function authorizeOwnerOrAdmin(DiagnosticCenter $dc): void
    {
        if (! auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($dc->user_id !== auth()->id() && ! $this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createDiagnostic(): void
    {
        if (! auth()->check()) {
            redirect()->route('login')->send();

            return;
        }
        $this->validate();

        $dc = DiagnosticCenter::create([
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
            $media = $dc->addMediaFromUrl($this->image_url)->usingFileName($dc->id.'.'.$extension)->toMediaCollection('diagnostic');
            $path = storage_path('app/public/Diagnostic/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        } elseif ($this->photo != null) {
            $media = $dc->addMedia($this->photo->getRealPath())->usingFileName($dc->name.'.'.$this->photo->extension())->toMediaCollection('diagnostic');
            $path = storage_path('app/public/Diagnostic/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'diagnostic-form');
    }

    public function selectDiagnosticForEdit(int $id): void
    {
        $dc = DiagnosticCenter::findOrFail($id);
        $this->authorizeOwnerOrAdmin($dc);

        $this->selectedId = $dc->id;
        $this->upazila_id = $dc->upazila_id;
        $this->name = $dc->name;
        $this->phone = $dc->phone ?? '';
        $this->address = $dc->address ?? '';
        $this->map = $dc->map ?? '';
        $this->status = $dc->status ?? 'active';

        $this->dispatch('open-modal', 'diagnostic-form');
    }

    public function updateDiagnostic(): void
    {
        if (! $this->selectedId) {
            return;
        }
        $this->validate();

        $dc = DiagnosticCenter::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($dc);

        $dc->update([
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'map' => $this->map ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url != null && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $dc->addMediaFromUrl($this->image_url)->usingFileName($dc->id.'.'.$extension)->toMediaCollection('diagnostic');
            $path = storage_path('app/public/Diagnostic/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        } elseif ($this->photo != null) {
            $media = $dc->addMedia($this->photo->getRealPath())->usingFileName($dc->name.'.'.$this->photo->extension())->toMediaCollection('diagnostic');
            $path = storage_path('app/public/Diagnostic/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'diagnostic-form');
    }

    public function confirmDelete(int $id): void
    {
        $dc = DiagnosticCenter::findOrFail($id);
        $this->authorizeOwnerOrAdmin($dc);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-diagnostic');
    }

    public function deleteSelectedDiagnostic(): void
    {
        if (! $this->selectedId) {
            return;
        }
        $dc = DiagnosticCenter::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($dc);
        $dc->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-diagnostic');
    }

    public function openDiagnosticForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'diagnostic-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'upazila_id', 'name', 'phone', 'address', 'map', 'status', 'photo', 'image_url']);
        $this->status = 'active';
    }

    public function render()
    {
        $query = DiagnosticCenter::with('upazila', 'user')->where('status', 'active');
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
        $diagnostics = $query->latest()->get();
        $upazilas = Upazila::whereBetween('id', [322, 327])->orderBy('name')->get();

        return view('livewire.web.diagnostic.diagnostic-center-component', compact('diagnostics', 'upazilas'))
            ->layout('components.layouts.web');
    }
}
