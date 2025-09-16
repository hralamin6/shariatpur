<?php

namespace App\Livewire\Web\BloodDonor;

use App\Models\Upazila;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class BloodDonorComponent extends Component
{
    use WithFileUploads, WithPagination;

    public ?int $selectedId = null;

    // Donor registration fields
    public ?int $upazila_id = null;

    public string $name = '';

    public string $phone = '';

    public string $email = '';

    public string $address = '';

    public string $blood_group = '';

    public ?string $last_donate_date = null;

    public string $donor_details = '';

    public string $donor_status = 'available';

    public int $total_donations = 0;

    // Search and filter
    public string $search = '';

    public ?int $filter_upazila_id = null;

    public string $filter_blood_group = '';

    public string $filter_status = '';

    // Photo upload (URL or File)
    public $photo; // uploaded file

    public string $image_url = '';

    // Details modal
    public ?int $detailsId = null;

    public array $donorDetails = [];

    // Delete confirmation
    public ?int $deleteId = null;

    protected function rules(): array
    {
        return [
            'upazila_id' => 'required|exists:upazilas,id',
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:150|unique:users,email,'.($this->selectedId ?? 'NULL').',id',
            'address' => 'nullable|string|max:500',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'last_donate_date' => 'nullable|date|before_or_equal:today',
            'donor_details' => 'nullable|string|max:2000',
            'donor_status' => 'required|in:available,unavailable,inactive',
            'total_donations' => 'integer|min:0',
            'photo' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url|max:1000',
        ];
    }

    protected function isAdmin(): bool
    {
        $user = auth()->user();

        return (bool) ($user && optional($user->role)->slug === 'admin');
    }

    protected function authorizeOwnerOrAdmin(User $user): void
    {
        if (! auth()->check()) {
            redirect()->route('login')->send();
        }
        if ($user->id !== auth()->id() && ! $this->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    public function mount(): void
    {
        $this->resetForm();
    }

    public function render()
    {
        $upazilas = Upazila::whereBetween('id', [322, 327])->orderBy('name')->get();

        $donors = User::with('upazila')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filter_upazila_id, fn ($q) => $q->where('upazila_id', $this->filter_upazila_id))
            ->when($this->filter_blood_group, fn ($q) => $q->where('blood_group', $this->filter_blood_group))
            ->when($this->filter_status, fn ($q) => $q->where('donor_status', $this->filter_status))
            ->latest()
            ->paginate(12);

        return view('livewire.web.blood-donor.blood-donor-component', [
            'donors' => $donors,
            'upazilas' => $upazilas,
        ])->layout('components.layouts.web');
    }

    public function selectDonorForEdit(int $donorId): void
    {
        $donor = User::findOrFail($donorId);
        $this->authorizeOwnerOrAdmin($donor);

        $this->selectedId = $donor->id;
        $this->upazila_id = $donor->upazila_id;
        $this->name = $donor->name;
        $this->phone = $donor->phone ?? '';
        $this->email = $donor->email;
        $this->address = $donor->address ?? '';
        $this->blood_group = $donor->blood_group ?? '';
        $this->last_donate_date = $donor->last_donate_date?->format('Y-m-d');
        $this->donor_details = $donor->donor_details ?? '';
        $this->donor_status = $donor->donor_status;
        $this->total_donations = $donor->total_donations;

        $this->dispatch('open-modal', 'donor-form');
    }

    public function saveDonor(): void
    {
        $this->validate();

        $donorData = [
            'upazila_id' => $this->upazila_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'blood_group' => $this->blood_group,
            'last_donate_date' => $this->last_donate_date,
            'donor_details' => $this->donor_details,
            'donor_status' => $this->donor_status,
            'total_donations' => $this->total_donations,
            'is_blood_donor' => true,
        ];

        if ($this->selectedId) {
            $donor = User::findOrFail($this->selectedId);
            $this->authorizeOwnerOrAdmin($donor);
            $donor->update($donorData);
            $message = 'Blood donor updated successfully.';
        } else {
            // If normal user is registering, use their account
            if (auth()->check() && ! $this->isAdmin()) {
                $donor = auth()->user();
                $this->authorizeOwnerOrAdmin($donor);
                $donor->update($donorData);
                $message = 'You are now registered as a blood donor.';
            } else {
                // Admin can create new donor user
                $donorData['password'] = bcrypt('password123');
                $donor = User::create($donorData);
                $message = 'Blood donor registered successfully.';
            }
        }

        // Handle media upload (URL first, then file)
        if (! empty($this->image_url) && function_exists('checkImageUrl') && checkImageUrl($this->image_url)) {
            $extension = pathinfo(parse_url($this->image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
            $media = $donor->addMediaFromUrl($this->image_url)
                ->usingFileName($donor->id.'.'.$extension)
                ->toMediaCollection('profile');
            $path = storage_path('app/public/User/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        } elseif ($this->photo) {
            $media = $donor->addMedia($this->photo->getRealPath())
                ->usingFileName(($donor->name ?: 'donor').'.'.$this->photo->extension())
                ->toMediaCollection('profile');
            $path = storage_path('app/public/User/'.$media->id.'/'.$media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        session()->flash('success', $message);
        $this->resetForm();
        $this->dispatch('close-modal', 'donor-form');
    }

    public function confirmDelete(int $donorId): void
    {
        $donor = User::findOrFail($donorId);
        $this->authorizeOwnerOrAdmin($donor);
        $this->deleteId = $donorId;
        $this->dispatch('open-modal', 'delete-donor');
    }

    public function deleteDonor(): void
    {
        if (! $this->deleteId) {
            return;
        }
        $donor = User::findOrFail($this->deleteId);
        $this->authorizeOwnerOrAdmin($donor);

        // Soft deactivate donor profile
        $donor->update(['is_blood_donor' => false, 'donor_status' => 'inactive']);

        session()->flash('success', 'Blood donor profile deactivated successfully.');
        $this->deleteId = null;
        $this->dispatch('close-modal', 'delete-donor');
    }

    public function showDetails(int $donorId): void
    {
        $donor = User::with('upazila')->findOrFail($donorId);

        $photoUrl = null;
        if (method_exists($donor, 'getFirstMediaUrl')) {
            $photoUrl = $donor->getFirstMediaUrl('profile', 'thumb') ?: $donor->getFirstMediaUrl('profile');
        }

        $this->detailsId = $donorId;
        $this->donorDetails = [
            'id' => $donor->id,
            'name' => $donor->name,
            'phone' => $donor->phone,
            'email' => $donor->email,
            'address' => $donor->address,
            'blood_group' => $donor->blood_group,
            'upazila' => $donor->upazila?->name,
            'last_donate_date' => $donor->last_donate_date?->format('d M Y'),
            'donor_details' => $donor->donor_details,
            'donor_status' => $donor->donor_status,
            'total_donations' => $donor->total_donations,
            'can_donate' => $donor->canDonate(),
            'days_until_next_donation' => $donor->getDaysUntilNextDonation(),
            'created_at' => $donor->created_at->format('d M Y'),
            'photo_url' => $photoUrl,
        ];

        $this->dispatch('open-modal', 'donor-details');
    }

    public function resetForm(): void
    {
        $this->reset(['selectedId', 'upazila_id', 'name', 'phone', 'email', 'address', 'blood_group', 'last_donate_date', 'donor_details', 'donor_status', 'total_donations', 'photo', 'image_url']);
        $this->donor_status = 'available';
        $this->total_donations = 0;
        $this->resetValidation();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        // Prefill for authenticated non-admin users to update their own account
        if (auth()->check() && ! $this->isAdmin()) {
            $user = auth()->user();
            $this->selectedId = $user->id;
            $this->name = $user->name ?? '';
            $this->email = $user->email ?? '';
            $this->phone = $user->phone ?? '';
            $this->address = $user->address ?? '';
            $this->upazila_id = $user->upazila_id ?? null;
            $this->blood_group = $user->blood_group ?? '';
            $this->last_donate_date = $user->last_donate_date?->format('Y-m-d');
            $this->donor_details = $user->donor_details ?? '';
            $this->donor_status = $user->donor_status ?? 'available';
            $this->total_donations = $user->total_donations ?? 0;
        }
        $this->dispatch('open-modal', 'donor-form');
    }
}
