<?php

namespace App\Livewire\Web\Institution;

use App\Models\InstitutionType;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class InstitutionTypeComponent extends Component
{
    use LivewireAlert;

    public string $name = '';
    public string $status = 'active';
    public ?int $selectedId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:150|unique:institution_types,name,' . ($this->selectedId ?? 'NULL') . ',id',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'status', 'selectedId']);
        $this->status = 'active';
    }

    public function createType(): void
    {
        $this->authorize('app.institution_types.create');

        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }

        $this->validate();

        InstitutionType::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->resetForm();

        $this->dispatch('close-modal', 'create-institution-type');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectTypeForEdit(int $id): void
    {
        $type = InstitutionType::findOrFail($id);
        $this->selectedId = $type->id;
        $this->name = $type->name;
        $this->status = $type->status;

        $this->dispatch('open-modal', 'edit-institution-type');
    }

    public function updateType(): void
    {
        $this->authorize('app.institution_types.edit');

        if (!$this->selectedId) {
            return;
        }

        $this->validate();

        $type = InstitutionType::findOrFail($this->selectedId);
        $type->update([
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->dispatch('close-modal', 'edit-institution-type');
        $this->alert('success', __('Data updated successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-institution-type');
    }

    public function deleteSelectedType(): void
    {
        $this->authorize('app.institution_types.delete');

        if (!$this->selectedId) {
            return;
        }

        $type = InstitutionType::findOrFail($this->selectedId);
        $type->delete();
        $this->selectedId = null;

        $this->dispatch('close-modal', 'delete-institution-type');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function render()
    {
        $types = InstitutionType::orderBy('name')->get();
        return view('livewire.web.institution.institution-type-component', compact('types'))
            ->layout('components.layouts.web');
    }
}

