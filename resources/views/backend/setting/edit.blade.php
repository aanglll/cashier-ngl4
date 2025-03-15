@extends('backend.apps.master')

@section('content')
<main class="content">
    <div class="container-fluid p-0">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0"><strong>Edit</strong> Settings</h1>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="mb-3">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" name="site_name" id="site_name" class="form-control" required maxlength="255" value="{{ old('site_name', $settings->site_name) }}">
                    </div>

                    <div class="mb-3">
                        <label for="site_logo" class="form-label">Site Logo</label>
                        <input type="file" name="site_logo" id="site_logo" class="form-control">
                        @if($settings->site_logo)
                            <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Site Logo" class="img-thumbnail mt-2" width="150">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="favicon" class="form-label">Favicon</label>
                        <input type="file" name="favicon" id="favicon" class="form-control">
                        @if($settings->favicon)
                            <img src="{{ asset('storage/' . $settings->favicon) }}" alt="Favicon" class="img-thumbnail mt-2" width="50">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="site_title" class="form-label">Site Title</label>
                        <input type="text" name="site_title" id="site_title" class="form-control" maxlength="255" value="{{ old('site_title', $settings->site_title) }}">
                    </div>

                    <div class="mb-3">
                        <label for="receipt_header" class="form-label">Receipt Header</label>
                        <textarea name="receipt_header" id="receipt_header" class="form-control">{{ old('receipt_header', $settings->receipt_header) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="receipt_footer" class="form-label">Receipt Footer</label>
                        <textarea name="receipt_footer" id="receipt_footer" class="form-control">{{ old('receipt_footer', $settings->receipt_footer) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Settings</button>
                </form>
            </div>
        </div>

    </div>
</main>
@endsection
