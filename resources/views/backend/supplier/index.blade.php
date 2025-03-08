@extends('backend.apps.master')

@section('content')
<main class="content">
    <div class="container-fluid p-0">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0"><strong>Supplier</strong> Management</h1>
            <a href="{{ route('backend.supplier.create') }}" class="btn btn-primary">
                Create <i data-feather="plus"></i>
            </a>
        </div>

        <div class="card flex-fill">
            <div class="table-responsive">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            {{-- <th>Created At</th> --}}
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $suppliers->total() - ($suppliers->currentPage() - 1) * $suppliers->perPage() - $loop->index }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td style="max-width: 450px;">{{ $supplier->address }}</td>
                                <td style="color: #206bc4">{{ $supplier->phone }}</td>
                                {{-- <td>{{ $supplier->created_at->format('Y-m-d') }}</td> --}}
                                <td>
                                    <a href="{{ route('backend.supplier.edit', $supplier->id) }}" class="btn btn-sm btn-warning">
                                        <i data-feather="edit"></i>
                                    </a>
                                    <form action="{{ route('backend.supplier.destroy', $supplier->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this supplier?')">
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

        <div class="mt-3 justify ">
            {{ $suppliers->links('pagination::tailwind') }}
        </div>

    </div>
</main>
@endsection
