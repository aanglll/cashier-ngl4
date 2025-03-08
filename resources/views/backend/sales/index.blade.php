@extends('backend.apps.master')

@section('content')
<main class="content">
    <div class="container-fluid p-0">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0"><strong>Sale</strong> Management</h1>
            <a href="{{ route('backend.sales.create') }}" class="btn btn-primary">
                Create <i data-feather="plus"></i>
            </a>
        </div>

        <div class="card flex-fill">
            <div class="table-responsive">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            {{-- <th>Sale ID</th> --}}
                            <th>Date</th>
                            <th>Customer</th>
                            {{-- <th>User</th> --}}
                            <th>Total Price</th>
                            {{-- <th>Status</th> --}}
                            {{-- <th>Cash Return</th> --}}
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            <tr>
                                <td>{{ $sales->total() - ($sales->currentPage() - 1) * $sales->perPage() - $loop->index }}</td>
                                {{-- <td>{{ $sale->id }}</td> --}}
                                <td>{{ $sale->created_at->translatedFormat('l, d M Y H:i:s') }}</td>
                                <td>{{ $sale->customer->name }}</td>
                                {{-- <td>{{ $sale->user->name }}</td> --}}
                                <td>{{ number_format($sale->total_price, 0, ',', '.') }}</td>
                                {{-- <td>{{ $sale->status }}</td> --}}
                                {{-- <td>{{ number_format($sale->cash_return, 2) }}</td> --}}
                                <td>
                                    <a href="{{ route('backend.sales.show', $sale->id) }}" class="btn btn-sm btn-info">
                                        <i data-feather="eye"></i>
                                    </a>
                                    {{-- <a href="{{ route('backend.sales.edit', $sale->id) }}" class="btn btn-sm btn-warning">
                                        <i data-feather="edit"></i>
                                    </a> --}}
                                    <form action="{{ route('backend.sales.destroy', $sale->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this sale?')">
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
            {{ $sales->links('pagination::bootstrap-5') }}
        </div>

    </div>
</main>
@endsection
