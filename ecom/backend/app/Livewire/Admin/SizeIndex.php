<?php

namespace App\Livewire\Admin;

use App\Models\Size;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SizeIndex extends Component
{
    public string $title = '';
    public ?int $editingId = null;
    public string $editingTitle = '';

    public function save(): void
    {
        $this->validate(['title' => 'required|string|max:255|unique:sizes,title']);
        Size::create(['title' => $this->title]);
        $this->reset('title');
        session()->flash('message', 'Size created.');
    }

    public function startEdit(int $id): void
    {
        $s = Size::find($id);
        $this->editingId = $s->id;
        $this->editingTitle = $s->title;
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
            'editingTitle' => ['required', 'string', 'max:255', Rule::unique('sizes', 'title')->ignore($this->editingId)],
        ]);
        Size::find($this->editingId)->update(['title' => $this->editingTitle]);
        $this->cancelEdit();
        session()->flash('message', 'Size updated.');
    }

    public function delete(int $id): void
    {
        Size::destroy($id);
        session()->flash('message', 'Size deleted.');
    }

    public function render()
    {
        return view('livewire.admin.size-index', [
            'sizes' => Size::withCount('products')->orderBy('title')->get(),
        ]);
    }
}
