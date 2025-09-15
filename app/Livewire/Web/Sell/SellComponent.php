<?php

namespace App\Livewire\Web\Sell;

use App\Models\Sell;
use App\Models\SellCategory;
use App\Models\Upazila;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class SellComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public ?int $selectedId = null;

    public ?int $sell_category_id = null;

    public ?int $upazila_id = null;

    public string $name = '';

    public string $phone = '';

    public string $details = '';

    public string $map_one = '';

    public string $address = '';

    public int $price = 0;

    public string $type = 'old'; // new | old

    public string $status = 'active';

    public string $search = '';

    public ?int $filter_category_id = null;

    public ?int $filter_upazila_id = null;

    public $photo; // uploaded file

    public string $image_url = '';

    public ?int $detailsId = null;

    public array $sellDetails = [];

    protected function rules(): array
    {
        return [
            'sell_category_id' => 'required|exists:sell_categories,id',
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150|unique:sells,name,'.($this->selectedId ?? 'NULL').',id',
            'phone' => 'nullable|string|max:30',
            'details' => 'nullable|string|max:1000',
            'map_one' => 'nullable|string|max:2000',
            'address' => 'nullable|string|max:255',
            'price' => 'nullable|integer|min:0',
            'type' => 'required|in:new,old',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|max:4096',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();

        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(Sell $sell): void
    {
        if (! auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($sell->user_id !== auth()->id() && ! $this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function createSell(): void
    {
        if (! auth()->check()) {
            redirect()->route('login')->send();

            return;
        }
        $this->validate();

        $sell = Sell::create([
            'user_id' => auth()->id(),
            'sell_category_id' => $this->sell_category_id,
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'details' => $this->details ?: null,
            'map_one' => $this->map_one ?: null,
            'address' => $this->address ?: null,
            'price' => $this->price ?: 0,
            'type' => $this->type,
            'status' => $this->status,
        ]);

        if ($this->image_url != null && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $sell->addMediaFromUrl($this->image_url)->usingFileName($sell->id.'.'.$extension)->toMediaCollection('sell');
            $path = storage_path('app/public/Sell/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        } elseif ($this->photo != null) {
            $media = $sell->addMedia($this->photo->getRealPath())->usingFileName($sell->name.'.'.$this->photo->extension())->toMediaCollection('sell');
            $path = storage_path('app/public/Sell/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'sell-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectSellForEdit(int $id): void
    {
        $sell = Sell::findOrFail($id);
        $this->authorizeOwnerOrAdmin($sell);

        $this->selectedId = $sell->id;
        $this->sell_category_id = $sell->sell_category_id;
        $this->upazila_id = $sell->upazila_id;
        $this->name = $sell->name;
        $this->phone = $sell->phone ?? '';
        $this->details = $sell->details ?? '';
        $this->map_one = $sell->map_one ?? '';
        $this->address = $sell->address ?? '';
        $this->price = (int) ($sell->price ?? 0);
        $this->type = $sell->type ?? 'old';
        $this->status = $sell->status ?? 'active';

        $this->dispatch('open-modal', 'sell-form');
    }

    public function updateSell(): void
    {
        if (! $this->selectedId) {
            return;
        }
        $this->validate();

        $sell = Sell::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($sell);

        $sell->update([
            'sell_category_id' => $this->sell_category_id,
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone ?: null,
            'details' => $this->details ?: null,
            'map_one' => $this->map_one ?: null,
            'address' => $this->address ?: null,
            'price' => $this->price ?: 0,
            'type' => $this->type,
            'status' => $this->status,
        ]);

        if ($this->image_url != null && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $sell->addMediaFromUrl($this->image_url)->usingFileName($sell->id.'.'.$extension)->toMediaCollection('sell');
            $path = storage_path('app/public/Sell/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        } elseif ($this->photo != null) {
            $media = $sell->addMedia($this->photo->getRealPath())->usingFileName($sell->name.'.'.$this->photo->extension())->toMediaCollection('sell');
            $path = storage_path('app/public/Sell/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->photo = null;
        $this->image_url = '';
        $this->dispatch('close-modal', 'sell-form');
        $this->alert('success', __('Data updated successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $sell = Sell::findOrFail($id);
        $this->authorizeOwnerOrAdmin($sell);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-sell');
    }

    public function deleteSelectedSell(): void
    {
        if (! $this->selectedId) {
            return;
        }
        $sell = Sell::findOrFail($this->selectedId);
        $this->authorizeOwnerOrAdmin($sell);
        $sell->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-sell');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function openSellForm(): void
    {
        $this->resetForm();
        if ($this->filter_category_id) {
            $this->sell_category_id = $this->filter_category_id;
        }
        $this->dispatch('open-modal', 'sell-form');
    }

    protected function resetForm(): void
    {
        $this->reset([
            'selectedId', 'sell_category_id', 'upazila_id', 'name', 'phone', 'details', 'map_one', 'address', 'price', 'type', 'status', 'photo', 'image_url',
        ]);
        $this->status = 'active';
        $this->type = 'old';
    }

    public function mount($cat_id = null): void
    {
        if ($cat_id) {
            $this->filter_category_id = (int) $cat_id;
            $this->sell_category_id = (int) $cat_id;
        }
    }

    public function render()
    {
        $query = Sell::with('category', 'upazila', 'user')->where('status', 'active');
        if ($this->filter_category_id) {
            $query->where('sell_category_id', $this->filter_category_id);
        }
        if ($this->filter_upazila_id) {
            $query->where('upazila_id', $this->filter_upazila_id);
        }
        if (filled($this->search)) {
            $s = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                    ->orWhere('phone', 'like', $s)
                    ->orWhere('details', 'like', $s)
                    ->orWhere('address', 'like', $s);
            });
        }
        $sells = $query->latest()->get();
        $categories = SellCategory::orderBy('name')->get();
        $upazilas = Upazila::whereBetween('id', [322, 327])->orderBy('name')->get();

        return view('livewire.web.sell.sell-component', compact('sells', 'categories', 'upazilas'))
            ->layout('components.layouts.web');
    }

    private function buildSellDetailsPayload(Sell $sell): array
    {
        $photoUrl = null;
        if (method_exists($sell, 'getFirstMediaUrl')) {
            $photoUrl = $sell->getFirstMediaUrl('sell', 'avatar') ?: $sell->getFirstMediaUrl('sell');
        }

        return [
            'name' => $sell->name,
            'category' => $sell->category?->name,
            'upazila' => $sell->upazila?->name,
            'price' => (int) ($sell->price ?? 0),
            'type' => $sell->type,
            'address' => $sell->address,
            'map_one' => $sell->map_one,
            'phone' => $sell->phone,
            'details' => $sell->details,
            'status' => $sell->status,
            'photo_url' => $photoUrl,
            'created_by' => $sell->user?->name,
            'created_at' => optional($sell->created_at)->format('d M Y'),
        ];
    }

    public function showDetails(int $id): void
    {
        $sell = Sell::with('category', 'upazila', 'user')->findOrFail($id);
        $this->detailsId = $id;
        $this->sellDetails = $this->buildSellDetailsPayload($sell);
        $this->dispatch('open-modal', 'sell-details');
    }

    public function sellDetails(int $id): void
    {
        $this->showDetails($id);
    }
}
