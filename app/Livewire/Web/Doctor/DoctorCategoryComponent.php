<?php

namespace App\Livewire\Web\Doctor;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DoctorCategoryComponent extends Component
{
    use LivewireAlert;

    public string $name = '';
    public string $bangla_name = '';
    public string $icon = '';
    public string $status = 'active';

    public ?int $selectedId = null;

    protected function rules(): array
    {
        $id = $this->selectedId ?: 'NULL';
        return [
            'name' => 'required|string|max:100|unique:doctor_categories,name,' . ($this->selectedId ?? 'NULL') . ',id',
            'bangla_name' => 'required|string|max:100|unique:doctor_categories,bangla_name,' . ($this->selectedId ?? 'NULL') . ',id',
            'icon' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function resetForm()
    {
        $this->reset(['name', 'bangla_name', 'icon', 'status', 'selectedId']);

    }

    public function createCategory(): void
    {
        $this->authorize('app.doctor_categories.create');

        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }
        $this->validate();

        \App\Models\DoctorCategory::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'bangla_name' => $this->bangla_name,
            'icon' => $this->icon ?: 'bxs-category',
            'status' => $this->status,
        ]);

        $this->reset(['name', 'bangla_name', 'icon', 'status']);
        $this->status = 'active';

        // Close the modal and inform the UI
        $this->dispatch('close-modal', 'create-doctor-category');
        $this->alert('success', __('Data updated successfully'));

    }

    public function selectCategoryForEdit(int $id): void
    {
        $category = \App\Models\DoctorCategory::findOrFail($id);
        $this->selectedId = $category->id;
        $this->name = $category->name;
        $this->bangla_name = $category->bangla_name ?? '';
        $this->icon = $category->icon ?? '';
        $this->status = $category->status ?? 'active';

        $this->dispatch('open-modal', 'edit-doctor-category');
    }

    public function updateCategory(): void
    {
        $this->authorize('app.doctor_categories.edit');

        if (!$this->selectedId) {
            return;
        }
        $this->validate();

        $category = \App\Models\DoctorCategory::findOrFail($this->selectedId);

        $category->update([
            'name' => $this->name,
            'bangla_name' => $this->bangla_name,
            'icon' => $this->icon ?: 'bxs-category',
            'status' => $this->status,
        ]);

        $this->dispatch('close-modal', 'edit-doctor-category');
        $this->alert('success', __('Data updated successfully'));

    }

    public function deleteSelectedCategory(): void
    {
        $this->authorize('app.doctor_categories.delete');

        if (!$this->selectedId) {
            return;
        }
        $category = \App\Models\DoctorCategory::findOrFail($this->selectedId);
        $category->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-doctor-category');
        $this->alert('success', __('Data deleted successfully'));

    }

    public function confirmDelete(int $id): void
    {
        $category = \App\Models\DoctorCategory::findOrFail($id);
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-doctor-category');
    }

    public function render()
    {
//        $this->authorize('app.doctor_categories.index');
        $doctorCategories = \App\Models\DoctorCategory::where('status', 'active')->get();
        return view('livewire.web.doctor.doctor-category-component',
        compact('doctorCategories'))->layout('components.layouts.web');
    }
}
