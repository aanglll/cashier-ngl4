@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Purchase</strong> Management</h1>
                <div>
                    <a href="javascript:void(0);" class="btn btn-secondary" onclick="printReceipt()">
                        Print <i data-feather="printer"></i>
                    </a>
                    <a href="{{ route('backend.purchases.create') }}" class="btn btn-primary">
                        Create <i data-feather="plus"></i>
                    </a>
                </div>
            </div>

            <div class="card flex-fill" id="printableArea">
                <div class="table-responsive">
                    <table class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>User</th>
                                <th>Total Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $purchase)
                                <tr>
                                    <td>{{ $purchases->total() - ($purchases->currentPage() - 1) * $purchases->perPage() - $loop->index }}
                                    </td>
                                    <td>{{ $purchase->created_at->translatedFormat('l, d M Y H:i:s') }}</td>
                                    <td>{{ $purchase->supplier->name ?? '-' }}</td>
                                    <td>{{ $purchase->user->name ?? '-' }}</td>
                                    <td>{{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('backend.purchases.show', $purchase->id) }}"
                                            class="btn btn-sm btn-info">
                                            <i data-feather="eye"></i>
                                        </a>
                                        <form action="{{ route('backend.purchases.destroy', $purchase->id) }}"
                                            method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this purchase?')">
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
                {{ $purchases->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </main>
    <script>
        function printReceipt() {
            var printContents = document.getElementById("printableArea").innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
@endsection
