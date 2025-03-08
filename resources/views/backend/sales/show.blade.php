@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Show</strong> Sales</h1>
                <a href="{{ route('backend.sales.index') }}" class="btn btn-secondary">Back to List</a>
            </div>

            <div class="card">
                <div class="card-body">
                    {{-- <h5>No Invoice: {{ $sale->created_at->format('d/m/Y') }}-{{ $sale->id }}</h5> --}}
                    <h5>Transaction Date: {{ $sale->created_at->translatedFormat('l, d M Y H:i:s') }}</h5>
                    <h5>Customer: {{ $sale->customer->name }}</h5>
                    <hr>
                    <h5>Sales Details</h5>
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
                            @foreach ($sale->salesDetails as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->product->product_name }}</td>
                                    <td>{{ number_format($detail->selling_price, 0, ',', '.') }}</td>
                                    <td>{{ $detail->qty }}</td>
                                    <td>{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <h5>Discount 10%: {{ number_format($sale->discount, 0, ',', '.') }}</h5>
                    <h5>PPN 11%: {{ number_format($sale->ppn, 0, ',', '.') }}</h5>
                    <h5>Total: {{ number_format($sale->total_price, 0, ',', '.') }}</h5>
                    <h5>Cash Paid: {{ number_format($sale->cash_paid, 0, ',', '.') }}</h5>
                    <h5>Cash Return: {{ number_format($sale->cash_return, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </main>

    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3">Invoice</h1>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body m-sm-3 m-md-5">
                            <div class="mb-4">
                                Hello <strong>Charles Hall</strong>,
                                <br />
                                This is the receipt for a payment of <strong>$268.00</strong> (USD) you made to AdminKit Demo.
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-muted">Payment No.</div>
                                    <strong>741037024</strong>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <div class="text-muted">Payment Date</div>
                                    <strong>June 2, 2023 - 03:45 pm</strong>
                                </div>
                            </div>

                            <hr class="my-4" />

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="text-muted">Client</div>
                                    <strong>
                                        Charles Hall
                                    </strong>
                                    <p>
                                        4183 Forest Avenue <br>
                                        New York City <br>
                                        10011 <br>
                                        USA <br>
                                        <a href="#">
                                            chris.wood@gmail.com
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <div class="text-muted">Payment To</div>
                                    <strong>
                                        AdminKit Demo LLC
                                    </strong>
                                    <p>
                                        354 Roy Alley <br>
                                        Denver <br>
                                        80202 <br>
                                        USA <br>
                                        <a href="#">
                                            info@adminkit.com
                                        </a>
                                    </p>
                                </div>
                            </div>

                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>AdminKit Demo Theme Customization</td>
                                        <td>2</td>
                                        <td class="text-end">$150.00</td>
                                    </tr>
                                    <tr>
                                        <td>Monthly Subscription </td>
                                        <td>3</td>
                                        <td class="text-end">$25.00</td>
                                    </tr>
                                    <tr>
                                        <td>Additional Service</td>
                                        <td>1</td>
                                        <td class="text-end">$100.00</td>
                                    </tr>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Subtotal </th>
                                        <th class="text-end">$275.00</th>
                                    </tr>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Shipping </th>
                                        <th class="text-end">$8.00</th>
                                    </tr>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Discount </th>
                                        <th class="text-end">5%</th>
                                    </tr>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Total </th>
                                        <th class="text-end">$268.85</th>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="text-center">
                                <p class="text-sm">
                                    <strong>Extra note:</strong>
                                    Please send all items at the same time to the shipping address.
                                    Thanks in advance.
                                </p>

                                <a href="#" class="btn btn-primary">
                                    Print this receipt
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection
