@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Create</strong> Product</h1>
                <a href="{{ route('backend.product.posts.index') }}" class="btn btn-secondary">
                    Back to List
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('backend.product.posts.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group mb-3 col-md-4">
                                <label for="product_name">Product Name</label>
                                <input type="text" name="product_name" id="product_name"
                                    class="form-control mt-2 @error('product_name') is-invalid @enderror"
                                    value="{{ old('product_name') }}" placeholder="Enter product name" required>
                                @error('product_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3 col-md-4">
                                <label for="id_category">Product Categories</label>
                                <select name="id_category" id="id_category"
                                    class="form-control mt-2 @error('id_category') is-invalid @enderror" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('id_category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->nama }} <!-- Sesuaikan dengan nama kolom yang sesuai -->
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_category')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3 col-md-4">
                                <label for="product_units">Product Units</label>
                                <select name="product_units" id="product_units"
                                    class="form-control mt-2 @error('product_units') is-invalid @enderror">
                                    <option value="">Select Product Unit</option>
                                    @foreach ($productUnits as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('product_units', $selectedUnit ?? '') == $unit->id ? 'selected' : '' }}>
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
                                <label for="purchase_price">Purchase Price</label>
                                <input type="number" step="0.01" name="purchase_price" id="purchase_price"
                                    class="form-control mt-2 @error('purchase_price') is-invalid @enderror"
                                    value="{{ old('purchase_price') }}" placeholder="Enter purchase price" required>
                                @error('purchase_price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3 col-md-4">
                                <label for="selling_price">Selling Price</label>
                                <input type="number" step="0.01" name="selling_price" id="selling_price"
                                    class="form-control mt-2 @error('selling_price') is-invalid @enderror"
                                    value="{{ old('selling_price') }}" placeholder="Enter selling price" required>
                                @error('selling_price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3 col-md-4 d-none">
                                <label for="stock">Stock</label>
                                <input type="number" name="stock" id="stock"
                                    class="form-control mt-2 @error('stock') is-invalid @enderror" value="0"
                                    placeholder="Enter stock">
                                @error('stock')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3 col-md-4">
                                <label for="barcode">Barcode</label>
                                <input type="text" name="barcode" id="barcode"
                                    class="form-control mt-2 @error('barcode') is-invalid @enderror"
                                    value="{{ old('barcode') }}" placeholder="Enter product barcode">
                                @error('barcode')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control mt-2 @error('description') is-invalid @enderror"
                                placeholder="Enter product description">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
