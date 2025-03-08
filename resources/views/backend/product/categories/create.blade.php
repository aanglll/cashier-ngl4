@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Create</strong> Product Category</h1>
                <a href="{{ route('backend.product.categories.index') }}" class="btn btn-secondary">
                    Back to List
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('backend.product.categories.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="nama">Category Name</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control mt-2 @error('nama') is-invalid @enderror" value="{{ old('nama') }}"
                                placeholder="Enter category name" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="mt-2 form-control @error('status') is-invalid @enderror"
                                required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
