<?php

namespace App\Livewire\Web\Launch;

use App\Models\LaunchRoute;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class LaunchRouteComponent extends Component
{
    use LivewireAlert;

    public string $name = '';
    public string $status = 'active';

    public ?int $selectedId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:150|unique:launch_routes,name,' . ($this->selectedId ?? 'NULL') . ',id',
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
        $this->authorize('app.launch_routes.create');

        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }

        $this->validate();

        LaunchRoute::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->resetForm();

        $this->dispatch('close-modal', 'create-launch-route');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectRouteForEdit(int $id): void
    {
        $route = LaunchRoute::findOrFail($id);
        $this->selectedId = $route->id;
        $this->name = $route->name;
        $this->status = $route->status ?? 'active';

        $this->dispatch('open-modal', 'edit-launch-route');
    }

    public function updateRoute(): void
    {
        $this->authorize('app.launch_routes.edit');

        if (!$this->selectedId) {
            return;
        }

        $this->validate();

        $route = LaunchRoute::findOrFail($this->selectedId);
        $route->update([
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->dispatch('close-modal', 'edit-launch-route');
        $this->alert('success', __('Data updated successfully'));
    }

    public function deleteSelectedRoute(): void
    {
        $this->authorize('app.launch_routes.delete');

        if (!$this->selectedId) {
            return;
        }
        $route = LaunchRoute::findOrFail($this->selectedId);
        $route->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-launch-route');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-launch-route');
    }

    public function render()
    {
        $launchRoutes = LaunchRoute::orderBy('name')->get();
        return view('livewire.web.launch.launch-route-component', compact('launchRoutes'))
            ->layout('components.layouts.web');
    }
}

