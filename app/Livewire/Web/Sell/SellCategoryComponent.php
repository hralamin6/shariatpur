<?php

namespace App\Livewire\Web\Sell;

use App\Models\SellCategory;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SellCategoryComponent extends Component
{
    use LivewireAlert;

    public string $name = '';

    public string $status = 'active';

    public ?int $selectedId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:150|unique:sell_categories,name,'.($this->selectedId ?? 'NULL').',id',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'status', 'selectedId']);
        $this->status = 'active';
    }

    public function createCategory(): void
    {
        $this->authorize('app.sell_categories.create');

        if (! auth()->check()) {
            redirect()->route('login')->send();

            return;
        }

        $this->validate();

        SellCategory::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->resetForm();

        $this->dispatch('close-modal', 'create-sell-category');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectCategoryForEdit(int $id): void
    {
        $cat = SellCategory::findOrFail($id);
        $this->selectedId = $cat->id;
        $this->name = $cat->name;
        $this->status = $cat->status ?? 'active';

        $this->dispatch('open-modal', 'edit-sell-category');
    }

    public function updateCategory(): void
    {
        $this->authorize('app.sell_categories.edit');

        if (! $this->selectedId) {
            return;
        }

        $this->validate();

        $cat = SellCategory::findOrFail($this->selectedId);
        $cat->update([
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->dispatch('close-modal', 'edit-sell-category');
        $this->alert('success', __('Data updated successfully'));
    }

    public function deleteSelectedCategory(): void
    {
        $this->authorize('app.sell_categories.delete');

        if (! $this->selectedId) {
            return;
        }
        $cat = SellCategory::findOrFail($this->selectedId);
        $cat->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-sell-category');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-sell-category');
    }

    public function render()
    {
        $categories = SellCategory::orderBy('name')->get();

        return view('livewire.web.sell.sell-category-component', compact('categories'))
            ->layout('components.layouts.web');
    }
}
