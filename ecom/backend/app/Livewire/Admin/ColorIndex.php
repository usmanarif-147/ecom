<?php

namespace App\Livewire\Admin;

use App\Models\Color;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ColorIndex extends Component
{
    public string $title = '';
    public string $code = '#000000';
    public ?int $editingId = null;
    public string $editingTitle = '';
    public string $editingCode = '';

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255|unique:colors,title',
            'code'  => ['required', 'string', 'size:7', 'regex:/^#[0-9A-Fa-f]{6}$/', 'unique:colors,code'],
        ]);

        Color::create(['title' => $this->title, 'code' => strtoupper($this->code)]);

        $this->reset('title');
        $this->code = '#000000';
        session()->flash('message', 'Color created.');
    }

    public function startEdit(int $id): void
    {
        $c = Color::find($id);
        $this->editingId    = $c->id;
        $this->editingTitle = $c->title;
        $this->editingCode  = $c->code;
        $this->resetErrorBag();
    }

    public function cancelEdit(): void
    {
        $this->editingId    = null;
        $this->editingTitle = '';
        $this->editingCode  = '';
        $this->resetErrorBag();
    }

    public function update(): void
    {
        $this->validate([
            'editingTitle' => ['required', 'string', 'max:255', Rule::unique('colors', 'title')->ignore($this->editingId)],
            'editingCode'  => ['required', 'string', 'size:7', 'regex:/^#[0-9A-Fa-f]{6}$/', Rule::unique('colors', 'code')->ignore($this->editingId)],
        ]);

        Color::find($this->editingId)->update(['title' => $this->editingTitle, 'code' => strtoupper($this->editingCode)]);

        $this->cancelEdit();
        session()->flash('message', 'Color updated.');
    }

    public function delete(int $id): void
    {
        Color::destroy($id);
        session()->flash('message', 'Color deleted.');
    }

    public function render()
    {
        return view('livewire.admin.color-index', [
            'colors' => Color::withCount('products')->orderBy('title')->get(),
        ]);
    }
}
