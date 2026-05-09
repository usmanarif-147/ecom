<?php

namespace App\Livewire\Admin;

use App\Data\AdminStaticData;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Product · Admin')]
class ProductForm extends Component
{
    public ?int $productId = null;
    public string $name = '';
    public string $category = 'T-Shirts';
    public string $price = '';
    public string $stock = '';
    public string $description = '';
    public string $image = '';
    public string $status = 'active';

    public function mount(?int $id = null): void
    {
        if ($id !== null) {
            $product = AdminStaticData::findProduct($id);
            if ($product) {
                $this->productId   = $product['id'];
                $this->name        = $product['name'];
                $this->category    = $product['category'];
                $this->price       = (string) $product['price'];
                $this->stock       = (string) $product['stock'];
                $this->image       = $product['image'];
                $this->status      = $product['status'];
                $this->description = '';
            }
        }
    }

    public function save()
    {
        // No real persistence in Phase 1 — just route back to the list.
        session()->flash('message', $this->productId
            ? "Product #{$this->productId} updated (demo only)."
            : 'Product created (demo only).');

        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        return view('livewire.admin.product-form', [
            'categories' => AdminStaticData::categories(),
            'pageTitle'  => $this->productId ? 'Edit Product' : 'New Product',
            'isEdit'     => $this->productId !== null,
        ]);
    }
}
