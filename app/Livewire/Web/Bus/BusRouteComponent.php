<?php

namespace App\Livewire\Web\Bus;

use App\Models\BusRoute;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BusRouteComponent extends Component
{
    use LivewireAlert;

    public string $name = '';
    public string $status = 'active';

    public ?int $selectedId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:150|unique:bus_routes,name,' . ($this->selectedId ?? 'NULL') . ',id',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'status', 'selectedId']);
        $this->status = 'active';
    }

    public function createRoute(): void
    {
        $this->authorize('app.bus_routes.create');

        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }

        $this->validate();

        BusRoute::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->resetForm();

        $this->dispatch('close-modal', 'create-bus-route');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectRouteForEdit(int $id): void
    {
        $route = BusRoute::findOrFail($id);
        $this->selectedId = $route->id;
        $this->name = $route->name;
        $this->status = $route->status ?? 'active';

        $this->dispatch('open-modal', 'edit-bus-route');
    }

    public function updateRoute(): void
    {
        $this->authorize('app.bus_routes.edit');

        if (!$this->selectedId) {
            return;
        }

        $this->validate();

        $route = BusRoute::findOrFail($this->selectedId);
        $route->update([
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->dispatch('close-modal', 'edit-bus-route');
        $this->alert('success', __('Data updated successfully'));
    }

    public function deleteSelectedRoute(): void
    {
        $this->authorize('app.bus_routes.delete');

        if (!$this->selectedId) {
            return;
        }
        $route = BusRoute::findOrFail($this->selectedId);
        $route->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-bus-route');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-bus-route');
    }

    public function render()
    {
        // $this->authorize('app.bus_routes.index');
        $busRoutes = BusRoute::orderBy('name')->get();
        return view('livewire.web.bus.bus-route-component', compact('busRoutes'))
            ->layout('components.layouts.web');
    }
}
