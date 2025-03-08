@extends('backend.apps.master')

@section('content')
<main class="content">
    <div class="container-fluid p-0">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0"><strong>Create</strong> Supplier</h1>
            <a href="{{ route('backend.supplier.index') }}" class="btn btn-secondary">
                Back to List
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('backend.supplier.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required maxlength="40" value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" id="address" class="form-control" required>{{ old('address') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" required maxlength="15" value="{{ old('phone') }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>

    </div>
</main>
@endsection
