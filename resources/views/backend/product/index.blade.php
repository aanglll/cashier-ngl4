@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="mb-3">
                <h1 class="h3 d-inline align-middle">Product</h1>
            </div>
            <div class="row">
                @if (auth()->user()->can('view products'))
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <a href="{{ route('backend.product.posts.index') }}" style="text-decoration: none;">
                                    <h5 class="card-title mb-2" style="color: #3b7ddd;">Posts</h5>
                                </a>
                                <a href="{{ route('backend.product.posts.index') }}"
                                    style="text-decoration: none; color: inherit;">
                                    <p class="card-text" style="color: black;">Navigates to a page listing all the products
                                        in
                                        the system, displaying details in a table format.</p>
                                </a>
                                {{-- <a href="#" class="card-link">Card link</a>
                        <a href="#" class="card-link">Another link</a> --}}
                            </div>
                        </div>
                    </div>
                @endif
                @if (auth()->user()->can('view product categories'))
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <a href="{{ route('backend.product.categories.index') }}" style="text-decoration: none;">
                                    <h5 class="card-title mb-2" style="color: #3b7ddd;">Categories</h5>
                                </a>
                                <a href="{{ route('backend.product.categories.index') }}"
                                    style="text-decoration: none; color: inherit;">
                                    <p class="card-text">Navigates to a page listing all the product categories in the
                                        system, displaying details in a table format.</p>
                                </a>
                                {{-- <a href="#" class="card-link">Card link</a>
                        <a href="#" class="card-link">Another link</a> --}}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row">
                @if (auth()->user()->can('view product units'))
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <a href="{{ route('backend.product.units.index') }}" style="text-decoration: none;">
                                    <h5 class="card-title mb-2" style="color: #3b7ddd;">Units</h5>
                                </a>
                                <a href="{{ route('backend.product.units.index') }}"
                                    style="text-decoration: none; color: inherit;">
                                    <p class="card-text">Navigates to a page listing all the product units in the system,
                                        displaying details in a table format.</p>
                                </a>
                                {{-- <a href="#" class="card-link">Card link</a>
                        <a href="#" class="card-link">Another link</a> --}}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </main>
@endsection
