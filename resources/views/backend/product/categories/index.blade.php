@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Product Categories</strong> Management</h1>
                <a href="{{ route('backend.product.categories.create') }}" class="btn btn-primary">Create <i data-feather="plus"></i></a>
            </div>


            {{-- <div style="font-size: 10px;" class="mb-4">
                <strong><a href="{{ route('home') }}" style="color: #3b7ddd; text-decoration: none;">DASHBOARD</a></strong>
                <span>/</span>
                <strong><a href="{{ route('backend.product') }}" style="color: #3b7ddd; text-decoration: none;">PRODUCT</a></strong>
                <span>/</span>
                <strong>CATEGORIES</strong>
            </div> --}}

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
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $categories->total() - ($categories->currentPage() - 1) * $categories->perPage() - $loop->index }}</td>
                                    <td>{{ $category->nama }}</td>
                                    <td>
                                        <span class="badge bg-{{ $category->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($category->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('backend.product.categories.edit', $category->id) }}" class="btn btn-sm btn-warning">
                                            <i data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('backend.product.categories.destroy', $category->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">
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
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </main>
@endsection
