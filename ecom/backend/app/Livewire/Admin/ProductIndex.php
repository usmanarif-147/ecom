<?php

namespace App\Livewire\Admin;

use App\Data\AdminStaticData;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Products · Admin')]
class ProductIndex extends Component
{
    public array $products = [];
    public string $search = '';
    public string $category = '';

    public function mount(): void
    {
        $this->products = AdminStaticData::products();
    }

    public function delete(int $id): void
    {
        $this->products = array_values(array_filter(
            $this->products,
            fn ($p) => $p['id'] !== $id
        ));
    }

    public function render()
    {
        $filtered = array_filter($this->products, function ($p) {
            $matchesSearch = $this->search === ''
                || str_contains(strtolower($p['name']), strtolower($this->search));
            $matchesCategory = $this->category === '' || $p['category'] === $this->category;
            return $matchesSearch && $matchesCategory;
        });

        return view('livewire.admin.product-index', [
            'visibleProducts' => array_values($filtered),
            'categories'      => AdminStaticData::categories(),
            'pageTitle'       => 'Products',
        ]);
    }
}
