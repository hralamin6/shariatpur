<?php

namespace App\Livewire\Web\Blog;

use App\Models\BlogCategory;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BlogCategoryComponent extends Component
{
    use LivewireAlert;

    public string $name = '';

    public string $status = 'active';

    public ?int $selectedId = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:150|unique:blog_categories,name,'.($this->selectedId ?? 'NULL').',id',
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
        $this->authorize('app.blog_categories.create');

        if (! auth()->check()) {
            redirect()->route('login')->send();

            return;
        }

        $this->validate();

        BlogCategory::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->resetForm();

        $this->dispatch('close-modal', 'create-blog-category');
        $this->alert('success', __('Data updated successfully'));
    }

    public function selectCategoryForEdit(int $id): void
    {
        $category = BlogCategory::findOrFail($id);
        $this->selectedId = $category->id;
        $this->name = $category->name;
        $this->status = $category->status ?? 'active';

        $this->dispatch('open-modal', 'edit-blog-category');
    }

    public function updateCategory(): void
    {
        $this->authorize('app.blog_categories.edit');

        if (! $this->selectedId) {
            return;
        }

        $this->validate();

        $category = BlogCategory::findOrFail($this->selectedId);
        $category->update([
            'name' => $this->name,
            'status' => $this->status,
        ]);

        $this->dispatch('close-modal', 'edit-blog-category');
        $this->alert('success', __('Data updated successfully'));
    }

    public function deleteSelectedCategory(): void
    {
        $this->authorize('app.blog_categories.delete');

        if (! $this->selectedId) {
            return;
        }
        $category = BlogCategory::findOrFail($this->selectedId);
        $category->delete();
        $this->selectedId = null;
        $this->dispatch('close-modal', 'delete-blog-category');
        $this->alert('success', __('Data deleted successfully'));
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', 'delete-blog-category');
    }

    public function render()
    {
        $categories = BlogCategory::orderBy('name')->get();

        return view('livewire.web.blog.blog-category-component', compact('categories'))
            ->layout('components.layouts.web');
    }
}
