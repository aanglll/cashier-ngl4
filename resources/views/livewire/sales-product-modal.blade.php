<div>
    <div class="mb-3 d-flex gap-2">
        <input type="text" class="form-control" placeholder="Search product..." wire:model.live="search">
        <select class="form-select" wire:model.live="category">
            <option value="">All Categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->category->nama }}</td>
                        <td>
                            @if($product->discount_product && $product->discount_product > 0)
                                {{ number_format($product->before_discount, 0, ',', '.') }} -> {{ number_format($product->selling_price, 0, ',', '.') }}
                            @else
                                {{ number_format($product->selling_price, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>{{ $product->discount_product ? $product->discount_product . '%' : '-' }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <button type="button" class="btn btn-primary select-product" data-id="{{ $product->id }}"
                                data-name="{{ $product->product_name }}" data-price="{{ $product->selling_price }}"
                                data-barcode="{{ $product->barcode }}" data-stock="{{ $product->stock }}" data-discount_product="{{ $product->discount_product }}" data-before_discount="{{ $product->before_discount }}">
                                Select
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $products->links() }}
</div>
