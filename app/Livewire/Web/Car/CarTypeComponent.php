<?php

namespace App\Livewire\Web\Car;

use App\Models\CarType;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CarTypeComponent extends Component
{
    use LivewireAlert;

    public string $name = '';

    public string $status = 'active';

    public ?int $selectedId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:150|unique:car_types,name,'.($this->selectedId ?? 'NULL').',id',
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
        $this->authorize('app.car_types.create');

        if (! auth()->check()) {
            redirect()->route('login')->send();

            return;
        }

        $this->validate();

        CarType::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->resetForm();

        $this->dispatch('close-modal', 'create-car-type');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectTypeForEdit(int $id): void
    {
        $type = CarType::findOrFail($id);
        $this->selectedId = $type->id;
        $this->name = $type->name;
        $this->status = $type->status ?? 'active';

        $this->dispatch('open-modal', 'edit-car-type');
    }

    public function updateType(): void
    {
        $this->authorize('app.car_types.edit');

        if (! $this->selectedId) {
            return;
        }

        $this->validate();

        $type = CarType::findOrFail($this->selectedId);
        $type->update([
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->dispatch('close-modal', 'edit-car-type');
        $this->alert('success', __('Data updated successfully'));
    }

    public function deleteSelectedType(): void
    {
        $this->authorize('app.car_types.delete');

        if (! $this->selectedId) {
            return;
        }
        $type = CarType::findOrFail($this->selectedId);
        $type->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-car-type');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-car-type');
    }

    public function render()
    {
        $types = CarType::orderBy('name')->get();

        return view('livewire.web.car.car-type-component', compact('types'))
            ->layout('components.layouts.web');
    }
}
