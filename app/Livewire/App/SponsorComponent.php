<?php

namespace App\Livewire\App;

use App\Models\Sponsor;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class SponsorComponent extends Component
{
    use WithPagination;
    use LivewireAlert;
    use WithFileUploads;

    public $selectedRows = [];
    public $selectPageRows = false;
    public $itemPerPage = 20;
    public $orderBy = 'id';
    public $searchBy = 'name';
    public $orderDirection = 'asc';
    public $search = '';
    public $itemStatus;

    public $photo = [];
    public $image_url;

    protected $queryString = [
        'search' => ['except' => ''],
        'itemStatus' => ['except' => null],
    ];

    public $sponsor;
    public $name, $title, $phone, $email, $address, $status = 'active', $expired_at, $user_id;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedItemPerPage()
    {
        $this->resetPage();
    }

    public function updatedSelectPageRows($value)
    {
        if ($value) {
            $this->selectedRows = $this->data->pluck('id')->map(fn ($id) => (string) $id);
        } else {
            $this->reset('selectedRows', 'selectPageRows');
        }
    }

    public function changeStatus(Sponsor $sponsor)
    {
        $this->authorize('app.sponsors.edit');
        $sponsor->status === 'active' ? $sponsor->update(['status' => 'inactive']) : $sponsor->update(['status' => 'active']);
        $this->alert('success', __('Data updated successfully'));
    }

    public function resetData()
    {
        $this->reset('name', 'title', 'phone', 'email', 'address', 'status', 'expired_at', 'user_id', 'sponsor', 'photo', 'image_url');
        $this->status = 'active';
    }

    public function saveData()
    {
        $this->authorize('app.sponsors.create');
        $this->user_id = Auth::id();

        $data = $this->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'expired_at' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
            'photo.*' => 'nullable|image|max:4096',
            'image_url' => 'nullable|url',
        ]);

        $sponsor = Sponsor::create($data);

        // Handle media uploads (URL or multiple files)
        if (! empty($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION);
            $media = $sponsor->addMediaFromUrl($this->image_url)
                ->usingFileName($sponsor->id.'.'.$extension)
                ->toMediaCollection('sponsorImages');

            $path = storage_path('app/public/Sponsor/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        } elseif (! empty($this->photo)) {
            foreach ($this->photo as $p) {
                $media = $sponsor->addMedia($p->getRealPath())
                    ->usingFileName($sponsor->id.'.'.$p->extension())
                    ->toMediaCollection('sponsorImages');
                $path = storage_path('app/public/Sponsor/'.$media->id.'/'.$media->file_name);
                if (file_exists($path)) {
                    @unlink($path);
                }
            }
        }

        $this->dispatch('dataAdded', dataId: 'item-id-'.$sponsor->id);
        $this->goToPage($this->getDataProperty()->lastPage());
        $this->alert('success', __('Data saved successfully!'));
        $this->resetData();
    }

    public function loadData(Sponsor $sponsor)
    {
        $this->resetData();
        $this->name = $sponsor->name;
        $this->title = $sponsor->title;
        $this->phone = $sponsor->phone;
        $this->email = $sponsor->email;
        $this->address = $sponsor->address;
        $this->status = $sponsor->status;
        $this->expired_at = optional($sponsor->expired_at)->format('Y-m-d\TH:i');
        $this->user_id = $sponsor->user_id;
        $this->sponsor = $sponsor;
    }

    public function editData()
    {
        $this->authorize('app.sponsors.edit');
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'expired_at' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
            'photo.*' => 'nullable|image|max:4096',
            'image_url' => 'nullable|url',
        ]);

        $this->sponsor->update($data);

        if (! empty($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION);
            $media = $this->sponsor->addMediaFromUrl($this->image_url)
                ->usingFileName($this->sponsor->id.'.'.$extension)
                ->toMediaCollection('sponsorImages');
            $path = storage_path('app/public/Sponsor/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                @unlink($path);
            }
        } elseif (! empty($this->photo)) {
            foreach ($this->photo as $p) {
                $media = $this->sponsor->addMedia($p->getRealPath())
                    ->usingFileName($this->sponsor->id.'.'.$p->extension())
                    ->toMediaCollection('sponsorImages');
                $path = storage_path('app/public/Sponsor/'.$media->id.'/'.$media->file_name);
                if (file_exists($path)) {
                    @unlink($path);
                }
            }
        }

        $this->dispatch('dataAdded', dataId: 'item-id-'.$this->sponsor->id);
        $this->alert('success', __('Data updated successfully'));
        $this->resetData();
    }

    public function deleteMedia(Sponsor $sponsor, $k)
    {
        $m = $sponsor->getMedia('sponsorImages');
        if (isset($m[$k])) {
            $m[$k]->delete();
            $this->alert('success', __('Image was deleted successfully'));
        }
    }

    public function getDataProperty()
    {
        return Sponsor::where($this->searchBy, 'like', '%'.$this->search.'%')
            ->orderBy($this->orderBy, $this->orderDirection)
            ->when($this->itemStatus, function ($query) {
                return $query->where('status', $this->itemStatus);
            })
            ->paginate($this->itemPerPage)
            ->withQueryString();
    }

    public function deleteMultiple()
    {
        $this->authorize('app.sponsors.delete');
        $sponsors = Sponsor::whereIn('id', $this->selectedRows)->get();
        foreach ($sponsors as $sponsor) {
            $sponsor->delete();
        }
        $this->selectPageRows = false;
        $this->selectedRows = [];
        $this->alert('success', __('Data deleted successfully'));
    }

    public function deleteSingle(Sponsor $sponsor)
    {
        $this->authorize('app.sponsors.delete');
        $sponsor->delete();
        $this->alert('success', __('Data deleted successfully'));
    }

    public function orderByDirection($field)
    {
        if ($this->orderBy === $field) {
            $this->orderDirection = $this->orderDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->orderBy = $field;
            $this->orderDirection = 'asc';
        }
    }

    public function render()
    {
        $this->authorize('app.sponsors.index');
        $items = $this->data;
        return view('livewire.app.sponsor-component', compact('items'));
    }
}

