@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Invoice</h1>
                <a href="javascript:void(0);" class="btn btn-primary" onclick="printReceipt()">Print this receipt</a>
                @if (auth()->user()->can('create sales'))
                    <a href="{{ route('backend.sales.create') }}" class="btn btn-secondary">Create new sales</a>
                @endif
                @if (auth()->user()->can('view sales'))
                    <a href="{{ route('backend.sales.index') }}" class="btn btn-secondary">Back to List</a>
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
                                    <div class="fw-bold">{{ $sale->id }}</div>
                                </div>
                                <div class="col-6 col-md-6">
                                    <div class="text-muted">Payment Date</div>
                                    <div class="fw-bold">{{ $sale->created_at->translatedFormat('l, d M Y H:i:s') }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-6">
                                    @if ($sale->customer)
                                        <div class="text-muted">Customer</div>
                                        <div class="fw-bold">{{ $sale->customer->name }}</div>
                                    @endif
                                </div>
                                <div class="col-6 col-md-6">
                                    @if ($sale->user->name)
                                        <div class="text-muted">Officer</div>
                                        <div class="fw-bold">{{ $sale->user->name }}</div>
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
                                        @foreach ($sale->salesDetails as $index => $detail)
                                            <tr>
                                                <td>{{ $detail->product->product_name }}</td>
                                                <td>{{ number_format($detail->selling_price, 0, ',', '.') }}</td>
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
                                                {{ number_format($sale->discount, 0, ',', '.') }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>PPN</th>
                                            <th class="text-end">
                                                {{ number_format($sale->ppn, 0, ',', '.') }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>Total </th>
                                            <th class="text-end">{{ number_format($sale->total_price, 0, ',', '.') }}</th>
                                        </tr>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>Cash Paid </th>
                                            <th class="text-end">{{ number_format($sale->cash_paid, 0, ',', '.') }}</th>
                                        </tr>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>Cash Return </th>
                                            <th class="text-end">{{ number_format($sale->cash_return, 0, ',', '.') }}</th>
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
