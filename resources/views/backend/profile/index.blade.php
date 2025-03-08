@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="mb-3">
                <h1 class="h3 d-inline align-middle">Profile</h1>
                {{-- <a class="badge bg-dark text-white ms-2" href="upgrade-to-pro.html">
                    Get more page examples
                </a> --}}
            </div>
            <div class="row">
                <div class="col-md-4 col-xl-3">
                    <div class="card mb-3">
                        {{-- <div class="card-header">
                            <h5 class="card-title mb-0">Profile Details</h5>
                        </div> --}}
                        <div class="card-body text-center">
                            <img src="{{ asset('assets/img/avatars/default-avatar-profile.webp') }}" alt="Christina Mason"
                                class="img-fluid rounded-circle mb-2" width="128" height="128" />
                            <h5 class="card-title mb-0">{{ $user->name }}</h5>
                            {{-- <div class="text-muted mb-2">Lead Developer</div> --}}

                            {{-- <div>
                                <a class="btn btn-primary btn-sm" href="#">Follow</a>
                                <a class="btn btn-primary btn-sm" href="#"><span data-feather="message-square"></span>
                                    Message</a>
                            </div> --}}
                        </div>
                        {{-- <hr class="my-0" />
                        <div class="card-body">
                            <h5 class="h6 card-title">Skills</h5>
                            <a href="#" class="badge bg-primary me-1 my-1">HTML</a>
                            <a href="#" class="badge bg-primary me-1 my-1">JavaScript</a>
                            <a href="#" class="badge bg-primary me-1 my-1">Sass</a>
                            <a href="#" class="badge bg-primary me-1 my-1">Angular</a>
                            <a href="#" class="badge bg-primary me-1 my-1">Vue</a>
                            <a href="#" class="badge bg-primary me-1 my-1">React</a>
                            <a href="#" class="badge bg-primary me-1 my-1">Redux</a>
                            <a href="#" class="badge bg-primary me-1 my-1">UI</a>
                            <a href="#" class="badge bg-primary me-1 my-1">UX</a>
                        </div> --}}
                        <hr class="my-0" />
                        <div class="card-body">
                            <h5 class="h6 card-title">About</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-1"><span data-feather="map-pin" class="feather-sm me-1"></span> <a
                                        href="#">{{ $user->address }}</a></li>

                                <li class="mb-1"><span data-feather="shield" class="feather-sm me-1"></span>
                                    <a href="#">{{ $user->user_priv }}</a>
                                </li>
                                <li class="mb-1"><span data-feather="phone" class="feather-sm me-1"></span> <a
                                        href="#">{{ $user->phone }}</a></li>
                            </ul>
                        </div>
                        {{-- <hr class="my-0" />
                        <div class="card-body">
                            <h5 class="h6 card-title">Elsewhere</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-1"><a href="#">staciehall.co</a></li>
                                <li class="mb-1"><a href="#">Twitter</a></li>
                                <li class="mb-1"><a href="#">Facebook</a></li>
                                <li class="mb-1"><a href="#">Instagram</a></li>
                                <li class="mb-1"><a href="#">LinkedIn</a></li>
                            </ul>
                        </div> --}}
                    </div>
                </div>

                <div class="col-md-8 col-xl-9">
                    <div class="card">
                        {{-- <div class="card-header">
                            <h5 class="card-title mb-0">Activities</h5>
                        </div> --}}
                        <div class="card-body h-100">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="d-flex align-items-start">
                                <form action="{{ route('backend.users.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name', $user->name) }}"
                                                required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email', $user->email) }}"
                                                required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                name="password">
                                            <small class="text-muted">Leave blank if you don't want to change the
                                                password.</small>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation">
                                        </div>
                                    </div>

                                    <div class="row d-none">
                                        <div class="mb-3 col-md-6">
                                            <label for="user_priv" class="form-label">User Privilege</label>
                                            <select class="form-select @error('user_priv') is-invalid @enderror"
                                                id="user_priv" name="user_priv" required>
                                                <option value="">-- Select Privilege --</option>
                                                <option value="superadmin"
                                                    {{ old('user_priv', $user->user_priv) == 'superadmin' ? 'selected' : '' }}>
                                                    Superadmin</option>
                                                <option value="admin"
                                                    {{ old('user_priv', $user->user_priv) == 'admin' ? 'selected' : '' }}>
                                                    Admin</option>
                                                <option value="officer"
                                                    {{ old('user_priv', $user->user_priv) == 'officer' ? 'selected' : '' }}>
                                                    Officer</option>
                                                <option value="warehouse admin"
                                                    {{ old('user_priv', $user->user_priv) == 'warehouse admin' ? 'selected' : '' }}>
                                                    Warehouse Admin</option>
                                            </select>
                                            @error('user_priv')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select @error('status') is-invalid @enderror" id="status"
                                                name="status" required>
                                                <option value="">-- Select Status --</option>
                                                <option value="active"
                                                    {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="inactive"
                                                    {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                                                    Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text"
                                                class="form-control @error('address') is-invalid @enderror" id="address"
                                                name="address" value="{{ old('address', $user->address) }}" required>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text"
                                                class="form-control @error('phone') is-invalid @enderror" id="phone"
                                                name="phone" value="{{ old('phone', $user->phone) }}" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update User</button>
                                </form>
                            </div>

                            {{-- <hr />
                            <div class="d-grid">
                                <a href="#" class="btn btn-primary">Load more</a>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
