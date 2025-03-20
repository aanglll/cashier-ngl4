@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Edit</strong> Product</h1>
                <a href="{{ route('backend.product.posts.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                    <form action="{{ route('backend.product.posts.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="form-group mb-3 col-md-4">
                                <label for="product_name">Product Name <span style="color: red;">*</span></label>
                                <input type="text" name="product_name" id="product_name"
                                    class="form-control mt-2 @error('product_name') is-invalid @enderror"
                                    value="{{ old('product_name', $product->product_name) }}"
                                    placeholder="Enter product name" required>
                                @error('product_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-4">
                                <label for="id_category">Product Categories <span style="color: red;">*</span></label>
                                <select name="id_category" id="id_category"
                                    class="form-control mt-2 @error('id_category') is-invalid @enderror" required>
                                    <option value="" disabled>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('id_category', $product->id_category) == $category->id ? 'selected' : '' }}>
                                            {{ $category->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_category')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-4">
                                <label for="product_units">Product Units <span style="color: red;">*</span></label>
                                <select name="product_units" id="product_units"
                                    class="form-control mt-2 @error('product_units') is-invalid @enderror" required>
                                    <option value="">Select Product Unit</option>
                                    @foreach ($productUnits as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('product_units', $product->product_units) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_units')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group mb-3 col-md-4">
                                <label for="purchase_price">Purchase Price <span style="color: red;">*</span></label>
                                <input type="number" step="0.01" name="purchase_price" id="purchase_price"
                                    class="form-control mt-2 @error('purchase_price') is-invalid @enderror"
                                    value="{{ old('purchase_price', $product->purchase_price) }}"
                                    placeholder="Enter purchase price" required>
                                @error('purchase_price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-4">
                                <label for="before_discount">Selling Price <span style="color: red;">*</span></label>
                                <input type="number" name="before_discount" id="before_discount"
                                    class="form-control mt-2 @error('before_discount') is-invalid @enderror"
                                    value="{{ old('before_discount', $product->before_discount) }}"
                                    placeholder="Enter Selling Price" required>
                                @error('before_discount')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-4 d-none">
                                <label for="stock">Stock <span style="color: red;">*</span></label>
                                <input type="number" name="stock" id="stock"
                                    class="form-control mt-2 @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}"
                                    placeholder="Enter stock">
                                @error('stock')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-4">
                                <label for="discount_product">Discount % <span style="color: red;">*</span></label>
                                <input type="number" name="discount_product" id="discount_product"
                                    class="form-control mt-2 @error('discount_product') is-invalid @enderror"
                                    value="{{ old('discount_product', $product->discount_product) }}" min="0" max="100"
                                    placeholder="Enter product discount" required>
                                @error('discount_product')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group mb-3 col-md-6">
                                <label for="selling_price">Total Selling Price <span style="color: red;">*</span></label>
                                <input type="number" step="0.01" name="selling_price" id="selling_price"
                                    class="form-control mt-2 @error('selling_price') is-invalid @enderror"
                                    value="{{ old('selling_price', $product->selling_price) }}" required readonly>
                                @error('selling_price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label for="barcode">Barcode <span style="color: red;">*</span></label>
                                <input type="text" name="barcode" id="barcode"
                                    class="form-control mt-2 @error('barcode') is-invalid @enderror"
                                    value="{{ old('barcode', $product->barcode) }}" placeholder="Enter product barcode"
                                    required>
                                @error('barcode')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control mt-2 @error('description') is-invalid @enderror"
                                placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const beforeDiscountInput = document.getElementById("before_discount");
            const discountInput = document.getElementById("discount_product");
            const sellingPriceInput = document.getElementById("selling_price");

            function calculateSellingPrice() {
                let beforeDiscount = parseFloat(beforeDiscountInput.value) || 0;
                let discount = parseFloat(discountInput.value) || 0;

                if (discount < 0) discount = 0;
                if (discount > 100) discount = 100;
                discountInput.value = discount;

                let discountedPrice = beforeDiscount - (beforeDiscount * (discount / 100));
                sellingPriceInput.value = discountedPrice.toFixed(0);
            }

            beforeDiscountInput.addEventListener("input", calculateSellingPrice);
            discountInput.addEventListener("input", function() {
                if (this.value < 0) this.value = 0;
                if (this.value > 100) this.value = 100;
                calculateSellingPrice();
            });

            calculateSellingPrice(); // Hitung ulang harga saat halaman dimuat
        });
    </script>
@endsection
