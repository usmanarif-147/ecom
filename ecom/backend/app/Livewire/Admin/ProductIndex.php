<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $categoryId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $product = Product::with('images')->find($id);
        if (! $product) {
            return;
        }

        foreach ($product->images as $img) {
            if (! str_starts_with($img->path, 'http') && ! str_starts_with($img->path, 'images/')) {
                Storage::disk('s3')->delete($img->path);
            }
        }

        $product->sizes()->detach();
        $product->colors()->detach();
        $product->delete();

        session()->flash('message', 'Product deleted.');
    }

    public function render()
    {
        $products = Product::query()
            ->with(['category:id,title', 'images' => fn($q) => $q->orderBy('id')->limit(1)])
            ->when($this->search !== '', fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->categoryId !== null, fn($q) => $q->where('category_id', $this->categoryId))
            ->orderBy('title')
            ->paginate(10);

        return view('livewire.admin.product-index', [
            'products'   => $products,
            'categories' => Category::orderBy('title')->get(['id', 'title']),
        ]);
    }
}
