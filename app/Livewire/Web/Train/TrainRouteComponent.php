<?php

namespace App\Livewire\Web\Train;

use App\Models\TrainRoute;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class TrainRouteComponent extends Component
{
    use LivewireAlert;

    public string $name = '';
    public string $status = 'active';

    public ?int $selectedId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:150|unique:train_routes,name,' . ($this->selectedId ?? 'NULL') . ',id',
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
        $this->authorize('app.train_routes.create');

        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }

        $this->validate();

        TrainRoute::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->resetForm();

        $this->dispatch('close-modal', 'create-train-route');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectRouteForEdit(int $id): void
    {
        $route = TrainRoute::findOrFail($id);
        $this->selectedId = $route->id;
        $this->name = $route->name;
        $this->status = $route->status ?? 'active';

        $this->dispatch('open-modal', 'edit-train-route');
    }

    public function updateRoute(): void
    {
        $this->authorize('app.train_routes.edit');

        if (!$this->selectedId) {
            return;
        }

        $this->validate();

        $route = TrainRoute::findOrFail($this->selectedId);
        $route->update([
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->dispatch('close-modal', 'edit-train-route');
        $this->alert('success', __('Data updated successfully'));
    }

    public function deleteSelectedRoute(): void
    {
        $this->authorize('app.train_routes.delete');

        if (!$this->selectedId) {
            return;
        }
        $route = TrainRoute::findOrFail($this->selectedId);
        $route->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-train-route');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-train-route');
    }

    public function render()
    {
        // $this->authorize('app.train_routes.index');
        $trainRoutes = TrainRoute::orderBy('name')->get();
        return view('livewire.web.train.train-route-component', compact('trainRoutes'))
            ->layout('components.layouts.web');
    }
}

