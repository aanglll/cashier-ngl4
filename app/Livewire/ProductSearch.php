<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::with('category');

        // Filter Kategori terlebih dahulu
        if ($this->category) {
            $query->where('id_category', $this->category);
        }

        // Setelah filter kategori, baru tambahkan pencarian
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('product_name', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%');
            });
        }

        $products = $query->paginate(10);
        $categories = ProductCategory::all();

        return view('livewire.product-search', compact('products', 'categories'));
    }
}
