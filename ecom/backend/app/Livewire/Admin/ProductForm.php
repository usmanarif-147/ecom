<?php

namespace App\Livewire\Admin;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public ?int $productId = null;
    public ?int $category_id = null;
    public string $title = '';
    public string $description = '';
    public string $price = '';
    public string $cost = '';
    public string $stock = '';
    public int $status = 1;
    public array $sizeIds = [];
    public array $colorIds = [];
    public array $newImages = [];
    public array $imagesToDelete = [];

    public function mount(?int $id = null): void
    {
        if ($id === null) {
            return;
        }

        $product = Product::with(['sizes:id', 'colors:id'])->findOrFail($id);

        $this->productId   = $product->id;
        $this->category_id = $product->category_id;
        $this->title       = $product->title;
        $this->description = (string) $product->description;
        $this->price       = (string) $product->price;
        $this->cost        = (string) $product->cost;
        $this->stock       = (string) $product->stock;
        $this->status      = $product->status->value;
        $this->sizeIds     = $product->sizes->pluck('id')->toArray();
        $this->colorIds    = $product->colors->pluck('id')->toArray();
    }

    public function removeExistingImage(int $imageId): void
    {
        if (! in_array($imageId, $this->imagesToDelete, true)) {
            $this->imagesToDelete[] = $imageId;
        }
    }

    public function save()
    {
        $this->validate([
            'category_id'   => 'required|integer|exists:categories,id',
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string|max:10000',
            'price'         => 'required|numeric|min:0',
            'cost'          => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'status'        => 'required|integer|in:0,1',
            'sizeIds'       => 'array',
            'sizeIds.*'     => 'integer|exists:sizes,id',
            'colorIds'      => 'array',
            'colorIds.*'    => 'integer|exists:colors,id',
            'newImages'     => 'array|max:8',
            'newImages.*'   => 'image|max:2048',
        ]);

        $product = DB::transaction(function () {
            $product = Product::updateOrCreate(
                ['id' => $this->productId],
                [
                    'category_id' => $this->category_id,
                    'title'       => $this->title,
                    'description' => $this->description !== '' ? $this->description : null,
                    'price'       => $this->price,
                    'cost'        => $this->cost,
                    'stock'       => $this->stock,
                    'status'      => ProductStatus::from($this->status),
                ],
            );

            $product->sizes()->sync($this->sizeIds);
            $product->colors()->sync($this->colorIds);

            foreach ($this->imagesToDelete as $imageId) {
                $img = Image::find($imageId);
                if (! $img) {
                    continue;
                }
                if (! str_starts_with($img->path, 'http') && ! str_starts_with($img->path, 'images/')) {
                    Storage::disk('s3')->delete($img->path);
                }
                $img->delete();
            }

            foreach ($this->newImages as $file) {
                $path = $file->store('products', 's3');
                Image::create(['product_id' => $product->id, 'path' => $path]);
            }

            return $product;
        });

        session()->flash('message', 'Product ' . ($this->productId ? 'updated' : 'created') . '.');

        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        $images = $this->productId
            ? Image::where('product_id', $this->productId)->whereNotIn('id', $this->imagesToDelete)->get()
            : collect();

        return view('livewire.admin.product-form', [
            'categories' => Category::orderBy('title')->get(['id', 'title']),
            'sizes'      => Size::orderBy('title')->get(['id', 'title']),
            'colors'     => Color::orderBy('title')->get(['id', 'title', 'code']),
            'images'     => $images,
            'isEdit'     => $this->productId !== null,
        ]);
    }
}
