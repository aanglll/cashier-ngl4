<div>
    <div class="mb-3 d-flex gap-2">
        <input type="text" class="form-control" placeholder="Search Products..." wire:model.live="search">
    
        <select class="form-select" wire:model.live="category">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->nama }}</option>
            @endforeach
        </select>
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
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{!! DNS1D::getBarcodeHTML($product->barcode, 'C128', 1) !!}</td>
                            {{-- <td>{!! DNS2D::getBarcodeHTML($product->barcode, 'QRCODE', 2,2) !!}</td> --}}
                            <td>{{ $product->category->nama }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <a href="{{ route('backend.product.posts.edit', $product->id) }}"
                                    class="btn btn-sm btn-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="-0.44 -0.44 14 14" fill="none"
                                        stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-edit" id="Edit--Streamline-Feather" height="14"
                                        width="14">
                                        <desc>Edit Streamline Icon: https://streamlinehq.com</desc>
                                        <path
                                            d="M6.0133333333333345 2.186666666666667H2.186666666666667a1.0933333333333335 1.0933333333333335 0 0 0 -1.0933333333333335 1.0933333333333335v7.653333333333334a1.0933333333333335 1.0933333333333335 0 0 0 1.0933333333333335 1.0933333333333335h7.653333333333334a1.0933333333333335 1.0933333333333335 0 0 0 1.0933333333333335 -1.0933333333333335v-3.826666666666667"
                                            stroke-width="0.88"></path>
                                        <path
                                            d="M10.113333333333335 1.366666666666667a1.15948 1.15948 0 0 1 1.6400000000000001 1.6400000000000001L6.5600000000000005 8.200000000000001l-2.186666666666667 0.5466666666666667 0.5466666666666667 -2.186666666666667 5.193333333333334 -5.193333333333334z"
                                            stroke-width="0.88"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('backend.product.posts.destroy', $product->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="-0.44 -0.44 14 14"
                                            fill="none" stroke="#ffffff" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-trash"
                                            id="Trash--Streamline-Feather" height="14" width="14">
                                            <desc>Trash Streamline Icon: https://streamlinehq.com</desc>
                                            <path
                                                d="m1.6400000000000001 3.2800000000000002 1.0933333333333335 0 8.746666666666668 0"
                                                stroke-width="0.88"></path>
                                            <path
                                                d="M10.386666666666668 3.2800000000000002v7.653333333333334a1.0933333333333335 1.0933333333333335 0 0 1 -1.0933333333333335 1.0933333333333335H3.826666666666667a1.0933333333333335 1.0933333333333335 0 0 1 -1.0933333333333335 -1.0933333333333335V3.2800000000000002m1.6400000000000001 0V2.186666666666667a1.0933333333333335 1.0933333333333335 0 0 1 1.0933333333333335 -1.0933333333333335h2.186666666666667a1.0933333333333335 1.0933333333333335 0 0 1 1.0933333333333335 1.0933333333333335v1.0933333333333335"
                                                stroke-width="0.88"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
