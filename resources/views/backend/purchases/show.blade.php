@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Show</strong> Purchases</h1>
                <a href="{{ route('backend.purchases.index') }}" class="btn btn-secondary">Back to List</a>
            </div>

            <div class="card">
                <div class="card-body">
                    {{-- <h5>No Invoice: {{ $purchase->created_at->format('d/m/Y') }}-{{ $purchase->id }}</h5> --}}
                    <h5>Transaction Date: {{ $purchase->created_at->translatedFormat('l, d M Y H:i:s') }}</h5>
                    <h5>Supplier: {{ $purchase->supplier->name }}</h5>
                    <hr>
                    <h5>Purchases Details</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase->purchaseDetails as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->product->product_name }}</td>
                                    <td>{{ number_format($detail->purchase_price, 0, ',', '.') }}</td>
                                    <td>{{ $detail->qty }}</td>
                                    <td>{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <h5>Discount 10%: {{ number_format($purchase->discount, 0, ',', '.') }}</h5>
                    <h5>PPN 11%: {{ number_format($purchase->ppn, 0, ',', '.') }}</h5>
                    <h5>Total: {{ number_format($purchase->total_price, 0, ',', '.') }}</h5>
                    <h5>Cash Paid: {{ number_format($purchase->cash_paid, 0, ',', '.') }}</h5>
                    <h5>Cash Return: {{ number_format($purchase->cash_return, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </main>
@endsection
