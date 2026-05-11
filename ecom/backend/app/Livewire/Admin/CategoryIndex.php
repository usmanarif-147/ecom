<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CategoryIndex extends Component
{
    public string $title = '';
    public ?int $editingId = null;
    public string $editingTitle = '';

    public function save(): void
    {
        $this->validate(['title' => 'required|string|max:255|unique:categories,title']);
        Category::create(['title' => $this->title]);
        $this->reset('title');
        session()->flash('message', 'Category created.');
    }

    public function startEdit(int $id): void
    {
        $cat = Category::find($id);
        $this->editingId = $cat->id;
        $this->editingTitle = $cat->title;
        $this->resetErrorBag();
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->editingTitle = '';
        $this->resetErrorBag();
    }

    public function update(): void
    {
        $this->validate([
            'editingTitle' => ['required', 'string', 'max:255', Rule::unique('categories', 'title')->ignore($this->editingId)],
        ]);
        Category::find($this->editingId)->update(['title' => $this->editingTitle]);
        $this->cancelEdit();
        session()->flash('message', 'Category updated.');
    }

    public function delete(int $id): void
    {
        Category::destroy($id);
        session()->flash('message', 'Category deleted.');
    }

    public function render()
    {
        return view('livewire.admin.category-index', [
            'categories' => Category::withCount('products')->orderBy('title')->get(),
        ]);
    }
}
