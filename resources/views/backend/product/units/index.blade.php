@extends('backend.apps.master')

@section('content')
<main class="content">
    <div class="container-fluid p-0">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0"><strong>Product Unit</strong> Management</h1>
            <a href="{{ route('backend.product.units.create') }}" class="btn btn-primary">Create <i data-feather="plus"></i></a>
        </div>

        <div class="card flex-fill">
            <div class="table-responsive">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productUnits as $productUnit)
                            <tr>
                                <td>{{ $productUnits->total() - ($productUnits->currentPage() - 1) * $productUnits->perPage() - $loop->index }}</td>
                                <td>{{ $productUnit->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $productUnit->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($productUnit->status) }}
                                    </span>
                                </td>
                                <td>{{ $productUnit->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('backend.product.units.edit', $productUnit->id) }}" class="btn btn-sm btn-warning">
                                        <i data-feather="edit"></i>
                                    </a>
                                    <form action="{{ route('backend.product.units.destroy', $productUnit->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product unit?')">
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
            {{ $productUnits->links('pagination::bootstrap-5') }}
        </div>

    </div>
</main>
@endsection
