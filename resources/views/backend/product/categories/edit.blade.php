@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Edit</strong> Product Category</h1>
                <a href="{{ route('backend.product.categories.index') }}" class="btn btn-secondary">
                    Back to List
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('backend.product.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="name">Category Name</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control mt-2 @error('nama') is-invalid @enderror"
                                value="{{ old('name', $category->nama) }}"
                                placeholder="Enter category name" required>
                            @error('nama')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="mt-2 form-control @error('status') is-invalid @enderror"
                                required>
                                <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
