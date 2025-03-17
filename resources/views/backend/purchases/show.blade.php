@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Invoice</h1>
                <a href="javascript:void(0);" class="btn btn-primary" onclick="printReceipt()">Print this receipt</a>
                @if (auth()->user()->can('create purchases'))
                    <a href="{{ route('backend.purchases.create') }}" class="btn btn-secondary">Create new Purchase</a>
                @endif
                @if (auth()->user()->can('view purchases'))
                    <a href="{{ route('backend.purchases.index') }}" class="btn btn-secondary">Back to List</a>
                @endif
            </div>

            <div class="row">
                <div class="col-1"></div>
                <div class="col-10" id="printableArea">
                    <div class="card">
                        <div class="card-body m-sm-3 m-md-5">
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="{{ $settings->site_name }}"
                                    width="50">
                                <strong>{!! $settings->site_name !!}</strong>,
                                {!! $settings->receipt_header !!}
                            </div>

                            <div class="row">
                                <div class="col-6 col-md-6">
                                    <div class="text-muted">Payment No.</div>
                                    <div class="fw-bold">{{ $purchase->id }}</div>
                                </div>
                                <div class="col-6 col-md-6">
                                    <div class="text-muted">Payment Date</div>
                                    <div class="fw-bold">{{ $purchase->created_at->translatedFormat('l, d M Y H:i:s') }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-6">
                                    @if ($purchase->supplier)
                                        <div class="text-muted">Supplier</div>
                                        <div class="fw-bold">{{ $purchase->supplier->name }}</div>
                                    @endif
                                </div>
                                <div class="col-6 col-md-6">
                                    @if ($purchase->user->name)
                                        <div class="text-muted">Officer</div>
                                        <div class="fw-bold">{{ $purchase->user->name }}</div>
                                    @endif
                                </div>
                            </div>


                            <hr class="my-4" />
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchase->purchaseDetails as $index => $detail)
                                            <tr>
                                                <td>{{ $detail->product->product_name }}</td>
                                                <td>{{ number_format($detail->purchase_price, 0, ',', '.') }}</td>
                                                <td>{{ $detail->qty }}</td>
                                                <td class="text-end">{{ number_format($detail->sub_total, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>Subtotal </th>
                                    <th class="text-end">2.920.000</th>
                                </tr> --}}
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>Discount </th>
                                            <th class="text-end">
                                                {{ number_format($purchase->discount, 0, ',', '.') }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>PPN </th>
                                            <th class="text-end">
                                                {{ number_format($purchase->ppn, 0, ',', '.') }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>Total </th>
                                            <th class="text-end">{{ number_format($purchase->total_price, 0, ',', '.') }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>Cash Paid </th>
                                            <th class="text-end">{{ number_format($purchase->cash_paid, 0, ',', '.') }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>Cash Return </th>
                                            <th class="text-end">{{ number_format($purchase->cash_return, 0, ',', '.') }}
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center">
                                <p class="text-sm">
                                    {!! $settings->receipt_footer !!}
                                </p>

                            </div>
                        </div>
                    </div>
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
            location.reload(); // Reload halaman agar kembali normal setelah cetak
        }
    </script>
@endsection
