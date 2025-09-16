<?php

namespace App\Livewire\Web\Train;

use App\Models\Train;
use App\Models\TrainRoute;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class TrainComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $train_route_id = null;
    public string $name = '';
    public string $phone = '';
    public string $details = '';
    public string $map_one = '';
    public string $map_two = '';
    public string $status = 'active';

    public string $search = '';
    public ?int $filter_route_id = null;

    // Image upload (URL or file)
    public $photo; // uploaded file
    public string $image_url = '';

    // Details modal state
    public ?int $detailsId = null;
    public array $trainDetails = [];

    protected function rules(): array
    {
        return [
            'train_route_id' => 'required|exists:train_routes,id',
            'name' => 'required|string|max:150|unique:trains,name,' . ($this->selectedId ?? 'NULL') . ',id',
            'phone' => 'nullable|string|max:30',
            'details' => 'nullable|string|max:1000',
            'map_one' => 'nullable|string|max:2000',
            'map_two' => 'nullable|string|max:2000',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();
        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(Train $train): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($train->user_id !== auth()->id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function showDetails(int $id): void
    {
        $train = Train::with('route', 'user')->findOrFail($id);
        $this->detailsId = $id;

        $photoUrl = null;
        if (method_exists($train, 'getFirstMediaUrl')) {
            $photoUrl = $train->getFirstMediaUrl('train', 'avatar') ?: $train->getFirstMediaUrl('train');
        }

        $this->trainDetails = [
            'name' => $train->name,
            'route' => $train->route?->name,
            'phone' => $train->phone,
            'details' => $train->details,
            'map_one' => $train->map_one,
            'map_two' => $train->map_two,
            'status' => $train->status,
            'photo_url' => $photoUrl,
            'created_by' => $train->user?->name,
            'created_at' => optional($train->created_at)->format('d M Y'),
        ];

        $this->dispatch('open-modal', 'train-details');
    }

    public function createTrain(): void
    {
        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        $train = Train::create([
            'user_id' => auth()->id(),
            'train_route_id' => $this->train_route_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'details' => $this->details ?: null,
            'map_one' => $this->map_one ?: null,
            'map_two' => $this->map_two ?: null,
            'status' => $this->status,
        ]);

        // Media upload (URL or File)
        if (!empty($this->image_url) && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $ext = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $train->addMediaFromUrl($this->image_url)
                ->usingFileName($train->id . '.' . $ext)
                ->toMediaCollection('train');
        } elseif ($this->photo) {
            $train->addMedia($this->photo->getRealPath())
                ->usingFileName(($train->id) . '.' . $this->photo->extension())
                ->toMediaCollection('train');
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'train-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectTrainForEdit(int $id): void
    {
        $train = Train::findOrFail($id);
        $this->authorizeOwnerOrAdmin($train);

        $this->selectedId = $train->id;
        $this->train_route_id = $train->train_route_id;
        $this->name = $train->name;
        $this->phone = $train->phone ?? '';
        $this->details = $train->details ?? '';
        $this->map_one = $train->map_one ?? '';
        $this->map_two = $train->map_two ?? '';
        $this->status = $train->status ?? 'active';

        $this->dispatch('open-modal', 'train-form');
    }

    public function updateTrain(): void
    {
        if (!$this->selectedId) return;
        $this->validate();

        $train = Train::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($train);

        $train->update([
            'train_route_id' => $this->train_route_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'details' => $this->details ?: null,
            'map_one' => $this->map_one ?: null,
            'map_two' => $this->map_two ?: null,
            'status' => $this->status,
        ]);

        if ($this->image_url!=null && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media =  $train->addMediaFromUrl($this->image_url)->usingFileName($train->id. '.' . $extension)->toMediaCollection('train');
            $path = storage_path("app/public/Train/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }

        }elseif($this->photo!=null){
            $media = $train->addMedia($this->photo->getRealPath())->usingFileName($train->name. '.' . $this->photo->extension())->toMediaCollection('train');
            $path = storage_path("app/public/Train/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'train-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $train = Train::findOrFail($id);
        $this->authorizeOwnerOrAdmin($train);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-train');
    }

    public function deleteSelectedTrain(): void
    {
        if (!$this->selectedId) return;
        $train = Train::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($train);
        $train->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-train');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function openTrainForm(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'train-form');
    }

    protected function resetForm(): void
    {
        $this->reset(['selectedId', 'train_route_id', 'name', 'phone', 'details', 'map_one', 'map_two', 'status', 'photo', 'image_url', 'detailsId', 'trainDetails']);
        $this->status = 'active';
        if ($this->filter_route_id) {
            $this->train_route_id = $this->filter_route_id;
        }
    }

    public function mount($route_id = null): void
    {
        if ($route_id) {
            $this->filter_route_id = (int) $route_id;
            $this->train_route_id = (int) $route_id;
        }
    }

    public function render()
    {
        $query = Train::with('route', 'user')->where('status', 'active');
        if ($this->filter_route_id) {
            $query->where('train_route_id', $this->filter_route_id);
        }
        if (filled($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('phone', 'like', $s)
                  ->orWhere('details', 'like', $s);
            });
        }
        $trains = $query->latest()->get();
        $routes = TrainRoute::orderBy('name')->get();

        return view('livewire.web.train.train-component', compact('trains', 'routes'))
            ->layout('components.layouts.web');
    }
}

