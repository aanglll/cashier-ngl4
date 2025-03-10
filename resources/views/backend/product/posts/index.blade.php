@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Product</strong> Management</h1>
                <a href="{{ route('backend.product.posts.create') }}" class="btn btn-primary">Create <i
                        data-feather="plus"></i></a>
            </div>

            @livewire('product-search')

              <div class="mt-3 d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </main>
@endsection
