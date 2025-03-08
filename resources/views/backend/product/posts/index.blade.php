@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Product</strong> Management</h1>
                <a href="{{ route('backend.product.posts.create') }}" class="btn btn-primary">Create <i
                        data-feather="plus"></i></a>
            </div>

            <div class="card flex-fill">
                <div class="table-responsive">
                    <table class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Barcode</th>
                                <th>Category</th>
                                {{-- <th>Purchase Price</th>
                                <th>Selling Price</th> --}}
                                <th>Stock</th>
                                {{-- <th>Created At</th> --}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $products->total() - ($products->currentPage() - 1) * $products->perPage() - $loop->index }}
                                    </td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>
                                        {{-- {{ $product->barcode }} --}}
                                        {!! DNS1D::getBarcodeHTML($product->barcode, 'C128', 1) !!}
                                    </td>
                                    <td>{{ $product->category->nama }}</td>
                                    {{-- <td>{{ number_format($product->purchase_price, 2) }}</td>
                                    <td>{{ number_format($product->selling_price, 2) }}</td> --}}
                                    <td>{{ $product->stock }}</td>
                                    {{-- <td>{{ $product->created_at->format('Y-m-d') }}</td> --}}
                                    <td>
                                        <a href="{{ route('backend.product.posts.edit', $product->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('backend.product.posts.destroy', $product->id) }}"
                                            method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i data-feather="trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </main>
@endsection
