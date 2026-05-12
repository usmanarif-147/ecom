<?php

namespace App\Livewire\Admin;

use App\Enums\ProductStatus;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadCsv extends Component
{
    use WithFileUploads;

    public $csv = null;
    public $counter = 0;

    public $rules = [];

    public function import()
    {
        $this->validate([
            'csv' => 'required|file|mimetypes:text/plain,text/csv,application/vnd.ms-excel',
        ]);

        $handle = fopen($this->csv->getRealPath(), 'r');
        $header = fgetcsv($handle);                    // first row → column names

        // dd(fgetcsv($handle));
        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            $row = array_combine($header, $data);
            $row['size']  = explode('|', $row['size']);    // ['1','3','5']
            $row['color'] = explode('|', $row['color']);
            $rows[] = $row;
        }
        fclose($handle);

        for ($i = 0; $i < count($rows); $i++) {
            $this->saveProduct($rows[$i]);
        }


        $this->counter++;
        $this->dispatch('show-progress-bar', counter: $this->counter);
    }

    private function saveProduct($data)
    {
        // dd($product);
        // $this->validate([
        //     'category_id'   => 'required|integer|exists:categories,id',
        //     'title'         => 'required|string|max:255',
        //     'description'   => 'nullable|string|max:10000',
        //     'price'         => 'required|numeric|min:0',
        //     'cost'          => 'required|numeric|min:0',
        //     'stock'         => 'required|integer|min:0',
        //     'status'        => 'required|integer|in:0,1',
        //     'sizeIds'       => 'array',
        //     'sizeIds.*'     => 'integer|exists:sizes,id',
        //     'colorIds'      => 'array',
        //     'colorIds.*'    => 'integer|exists:colors,id',
        //     'newImages'     => 'array|max:8',
        //     'newImages.*'   => 'image|max:2048',
        // ]);

        $product = Product::create(
            [
                'category_id' => $data['category'],
                'title'       => $data['title'],
                'description' => $data['description'] !== '' ? $data['description'] : null,
                'price'       => $data['price'],
                'cost'        => $data['cost'],
                'stock'       => $data['stock'],
                'status'      => ProductStatus::from($data['status']),
            ],
        );

        $product->sizes()->sync($data['size']);
        $product->colors()->sync($data['color']);
    }

    public function render()
    {
        return view('livewire.admin.upload-csv');
    }
}
