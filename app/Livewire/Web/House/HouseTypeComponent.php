<?php

namespace App\Livewire\Web\House;

use App\Models\HouseType;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class HouseTypeComponent extends Component
{
    use LivewireAlert;

    public string $name = '';
    public string $status = 'active';

    public ?int $selectedId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:150|unique:house_types,name,' . ($this->selectedId ?? 'NULL') . ',id',
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
        $this->authorize('app.house_types.create');

        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }

        $this->validate();

        HouseType::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->resetForm();

        $this->dispatch('close-modal', 'create-house-type');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectTypeForEdit(int $id): void
    {
        $route = HouseType::findOrFail($id);
        $this->selectedId = $route->id;
        $this->name = $route->name;
        $this->status = $route->status ?? 'active';

        $this->dispatch('open-modal', 'edit-house-type');
    }

    public function updateType(): void
    {
        $this->authorize('app.house_types.edit');

        if (!$this->selectedId) {
            return;
        }

        $this->validate();

        $route = HouseType::findOrFail($this->selectedId);
        $route->update([
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->dispatch('close-modal', 'edit-house-type');
        $this->alert('success', __('Data updated successfully'));
    }

    public function deleteSelectedType(): void
    {
        $this->authorize('app.house_types.delete');

        if (!$this->selectedId) {
            return;
        }
        $route = HouseType::findOrFail($this->selectedId);
        $route->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-house-type');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-house-type');
    }

    public function render()
    {
        $types = HouseType::orderBy('name')->get();
        return view('livewire.web.house.house-type-component', compact('types'))
            ->layout('components.layouts.web');
    }
}

