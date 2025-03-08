@extends('backend.apps.master')

@section('content')
<main class="content">
    <div class="container-fluid p-0">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0"><strong>Stock</strong> Management</h1>
            {{-- <a href="#" class="btn btn-primary disabled">
                Add Stock <i data-feather="plus"></i>
            </a> --}}
        </div>

        <div class="card flex-fill">
            <div class="table-responsive">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Stock In</th>
                            <th>Stock Out</th>
                            <th>Current Stock</th>
                            <th>Source</th>
                            <th>Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stocks as $stock)
                            <tr>
                                <td>{{ $stocks->total() - ($stocks->currentPage() - 1) * $stocks->perPage() - $loop->index }}</td>
                                <td>{{ $stock->product->product_name }}</td>
                                <td class="text-success">+{{ $stock->stock_in }}</td>
                                <td class="text-danger">-{{ $stock->stock_out }}</td>
                                <td>
                                    <span class="badge bg-{{ $stock->current_stock > 0 ? 'success' : 'danger' }}">
                                        {{ $stock->current_stock }}
                                    </span>
                                </td>
                                <td>{{ ucfirst($stock->source) }}</td>
                                <td>{{ $stock->updated_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3 justify ">
            {{ $stocks->links('pagination::tailwind') }}
        </div>
    </div>
</main>
@endsection
