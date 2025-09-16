<?php

namespace App\Livewire\Web\Serviceman;

use App\Models\ServicemanType;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ServicemanTypeComponent extends Component
{
    use LivewireAlert;

    public string $name = '';
    public string $status = 'active';

    public ?int $selectedId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:150|unique:serviceman_types,name,' . ($this->selectedId ?? 'NULL') . ',id',
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
        $this->authorize('app.serviceman_types.create');

        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }

        $this->validate();

        ServicemanType::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->resetForm();

        $this->dispatch('close-modal', 'create-serviceman-type');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectTypeForEdit(int $id): void
    {
        $type = ServicemanType::findOrFail($id);
        $this->selectedId = $type->id;
        $this->name = $type->name;
        $this->status = $type->status ?? 'active';

        $this->dispatch('open-modal', 'edit-serviceman-type');
    }

    public function updateType(): void
    {
        $this->authorize('app.serviceman_types.edit');

        if (!$this->selectedId) {
            return;
        }

        $this->validate();

        $type = ServicemanType::findOrFail($this->selectedId);
        $type->update([
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->dispatch('close-modal', 'edit-serviceman-type');
        $this->alert('success', __('Data updated successfully'));
    }

    public function deleteSelectedType(): void
    {
        $this->authorize('app.serviceman_types.delete');

        if (!$this->selectedId) {
            return;
        }
        $type = ServicemanType::findOrFail($this->selectedId);
        $type->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-serviceman-type');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-serviceman-type');
    }

    public function render()
    {
        $types = ServicemanType::orderBy('name')->get();
        return view('livewire.web.serviceman.serviceman-type-component', compact('types'))
            ->layout('components.layouts.web');
    }
}

