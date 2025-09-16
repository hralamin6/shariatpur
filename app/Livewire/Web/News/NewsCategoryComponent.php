<?php

namespace App\Livewire\Web\News;

use App\Models\NewsCategory;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class NewsCategoryComponent extends Component
{
    use LivewireAlert;

    public string $name = '';
    public string $status = 'active';

    public ?int $selectedId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:150|unique:news_categories,name,' . ($this->selectedId ?? 'NULL') . ',id',
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
        $this->authorize('app.news_categories.create');

        if (!auth()->check()) {
            redirect()->route('login')->send();
            return;
        }

        $this->validate();

        NewsCategory::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->resetForm();

        $this->dispatch('close-modal', 'create-news-category');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectCategoryForEdit(int $id): void
    {
        $cat = NewsCategory::findOrFail($id);
        $this->selectedId = $cat->id;
        $this->name = $cat->name;
        $this->status = $cat->status ?? 'active';

        $this->dispatch('open-modal', 'edit-news-category');
    }

    public function updateCategory(): void
    {
        $this->authorize('app.news_categories.edit');

        if (!$this->selectedId) {
            return;
        }

        $this->validate();

        $cat = NewsCategory::findOrFail($this->selectedId);
        $cat->update([
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->dispatch('close-modal', 'edit-news-category');
        $this->alert('success', __('Data updated successfully'));
    }

    public function deleteSelectedCategory(): void
    {
        $this->authorize('app.news_categories.delete');

        if (!$this->selectedId) {
            return;
        }
        $cat = NewsCategory::findOrFail($this->selectedId);
        $cat->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-news-category');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-news-category');
    }

    public function render()
    {
        $categories = NewsCategory::orderBy('name')->get();
        return view('livewire.web.news.news-category-component', compact('categories'))
            ->layout('components.layouts.web');
    }
}

