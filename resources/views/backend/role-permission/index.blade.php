@extends('backend.apps.master')

@section('content')
<main class="content">
    <div class="container-fluid p-0">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0"><strong>Role</strong> Management</h1>
            <a href="{{ route('role-permission.create') }}" class="btn btn-primary">Create <i data-feather="plus"></i></a>
        </div>

        <div class="card flex-fill">
            <div class="table-responsive">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Role Name</th>
                            <th>Guard Name</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $roles->total() - ($roles->currentPage() - 1) * $roles->perPage() - $loop->index }}</td>
                                <td>{{ ucfirst($role->name) }}</td>
                                <td>{{ $role->guard_name }}</td>
                                <td>{{ $role->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('role-permission.edit', $role->id) }}" class="btn btn-sm btn-warning">
                                        <i data-feather="edit"></i>
                                    </a>
                                    <form action="{{ route('role-permission.destroy', $role->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this role?')">
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
            {{ $roles->links('pagination::tailwind') }}
        </div>

    </div>
</main>
@endsection
