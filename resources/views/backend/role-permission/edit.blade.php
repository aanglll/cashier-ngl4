@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Edit</strong> Role</h1>
                <a href="{{ route('role-permission.index') }}" class="btn btn-secondary">
                    Back to List
                </a>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <form action="{{ route('role-permission.update', $role->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
                                </div>

                                <div class="mb-3" style="display:none;">
                                    <label for="guard_name" class="form-label">Guard Name <span class="text-danger">*</span></label>
                                    <select class="form-control" id="guard_name" name="guard_name" required>
                                        <option value="web" {{ $role->guard_name == 'web' ? 'selected' : '' }}>web</option>
                                    </select>
                                </div>

                                <!-- Permissions Section -->
                                @php
                                    $permissionSections = [
                                        'Dashboard' => 'dashboards',
                                        'Sale' => 'sales',
                                        'Purchase' => 'purchases',
                                        'Stock' => 'stocks',
                                        'Product' => 'products',
                                        'Product Category' => 'product categories',
                                        'Product Unit' => 'product units',
                                        'Supplier' => 'suppliers',
                                        'Customer' => 'customers',
                                        'Setting' => 'settings',
                                        'User' => 'users',
                                        'Role' => 'role'
                                    ];
                                @endphp

                                @foreach ($permissionSections as $sectionTitle => $sectionPrefix)
                                    <div class="permissions-list mt-4">
                                        <h3 class="page-title fw-semibold fs-18 mb-0">{{ $sectionTitle }} Permissions</h3>
                                        <div class="row">
                                            @foreach ($permissions as $permission)
                                                @if (str_starts_with($permission->name, "create $sectionPrefix") ||
                                                     str_starts_with($permission->name, "edit $sectionPrefix") ||
                                                     str_starts_with($permission->name, "delete $sectionPrefix") ||
                                                     str_starts_with($permission->name, "view $sectionPrefix"))
                                                    <div class="col-md-6">
                                                        <div class="form-check mt-3">
                                                            <input type="checkbox" class="form-check-input"
                                                                   name="permissions[]" value="{{ $permission->id }}"
                                                                   {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                                            <label class="form-check-label">{{ $permission->name }}</label>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <button type="submit" class="btn btn-primary mt-4">Update Role</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
