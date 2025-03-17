@extends('backend.apps.master')

@section('content')
    <style>
        .modal {
            display: none;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050 !important;
            overflow: auto;
            overflow-x: hidden;
        }

        .modal-dialog {
            position: relative;
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
            transform: none !important;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 3px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal-body {
            padding: 15px 20px;
            max-height: 400px;
            overflow-y: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 10px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }

        .table thead th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .select-product {
            min-width: 90px;
            padding: 5px 12px;
            font-size: 14px;
            font-weight: bold;
            background-color: #1cbb8c;
            color: white;
            border: none;
            border-radius: 3px;
            transition: background-color 0.3s;
        }

        .select-product:hover {
            background-color: #3ec59d;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .select2-container--default .select2-selection--single {
            height: 32px;
            line-height: 32px;
            border: 1px solid #ddd;
            border-radius: 3.2px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px;
            color: #495057;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: #66afe9;
            outline: none;
            box-shadow: 0 0 5px rgba(102, 175, 233, .6);
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #aaa;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #495057;
            opacity: 1;
        }
    </style>
    <main class="content">
        <div class="container-fluid p-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Create</strong> Sales</h1>
                @if (auth()->user()->can('view sales'))
                    <a href="{{ route('backend.sales.index') }}" class="btn btn-secondary">Back to List</a>
                @endif
            </div>
            <form action="{{ route('backend.sales.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="card col-md-4">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                                </div>
                            @endif


                            <div class="mb-3">
                                {{-- <div class="col-md-6">
                                <h5>No Invoice: {{ $invoiceNumber }}</h5>
                            </div> --}}
                                <h5>Transaction Date: {{ now()->format('d-m-Y') }}</h5>
                            </div>

                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="customer_id" class="mb-2">Customer</label>
                                    <select name="customer_id" id="customer_id" class="form-control">
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
                                data-bs-target="#createCustomerModal">Create Customer</button>

                            <h5>Products</h5>

                            {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
                            rel="stylesheet"
                            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
                            crossorigin="anonymous"> --}}

                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                                integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
                            </script>

                            <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js"
                                integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ=="
                                crossorigin="anonymous" referrerpolicy="no-referrer"></script>

                            <style>
                                #reader {
                                    width: 100%;

                                    border-radius: 30px;
                                }

                                #result {

                                    text-align: center;

                                    font-size: 1.5rem;
                                }
                            </style>

                            <div id="reader" class="rounded"></div>

                            <div id="result"></div>

                            <button type="button" class="btn btn-success mt-3" id="add-product">Choose Product</button>

                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="discount">Discount</label>
                                    <input type="text" name="discount" id="discount" class="form-control" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="ppn">PPN</label>
                                    <input type="text" name="ppn" id="ppn" class="form-control" readonly>
                                </div>
                            </div>

                            <label for="total_price" class="">Total</label>
                            <input type="text" name="total_price" id="total_price" class="form-control" readonly>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="cash_paid">Cash Paid</label>
                                    <input type="number" name="cash_paid" id="cash_paid" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="cash_return">Cash Return</label>
                                    <input type="text" name="cash_return" id="cash_return" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">Save Transaction</button>
                            </div>
                        </div>
                    </div>
                    <div class="card col-md-8">
                        <input type="text" id="barcode-input" class="form-control mt-3"
                            placeholder="Scan or enter the barcode and then press Enter">
                        <div class="table-responsive">
                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Stock</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="sales-details-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    @include('backend.sales.modal-product')

    @include('backend.sales.modal-customer')

    <script>
        const scanner = new Html5QrcodeScanner('reader', {
            qrbox: {
                width: 300,
                height: 200,
            },
            fps: 20,
        });

        scanner.render(success, error);

        function success(result) {
            addProductByBarcode(result);
        }

        function error(err) {
            console.error(err);
        }

        function formatRupiah(angka) {
            return '' + angka.toLocaleString('id-ID');
        }

        function addProductByBarcode(barcode) {
            const productElement = document.querySelector(`.select-product[data-barcode="${barcode}"]`);

            if (productElement) {
                const id = productElement.dataset.id;
                const name = productElement.dataset.name;
                const price = parseFloat(productElement.dataset.price);
                const stock = parseInt(productElement.dataset.stock);

                addProductToTable(barcode, name, price, stock);
            } else {
                alert('Produk tidak ditemukan!');
            }
        }

        document.getElementById('barcode-input').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                let barcode = this.value.trim();

                if (barcode !== '') {
                    fetch(`/get-product-by-barcode/${barcode}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                addProductToTable(data.product.barcode, data.product.name, data.product.price,
                                    data.product.stock);
                            } else {
                                alert('Produk tidak ditemukan!');
                            }
                        })
                        .catch(error => console.error('Error:', error));

                    this.value = '';
                }
            }
        });

        function addProductToTable(barcode, name, price, stock) {
            let existingRow = document.querySelector(`.product-barcode[data-barcode="${barcode}"]`);

            if (existingRow) {
                let qtyInput = existingRow.closest('tr').querySelector('.qty');
                let newQty = parseInt(qtyInput.value) + 1;

                if (newQty > stock) {
                    alert('Stok tidak cukup!');
                    return;
                }

                qtyInput.value = newQty;
                qtyInput.dispatchEvent(new Event('input'));
            } else {
                const newRow = `
                    <tr>
                        <td class="row-number"></td>
                        <td class="d-none">
                            <span class="product-barcode" data-barcode="${barcode}">${barcode}</span>
                        </td>
                        <td>
                            <span class="product-name">${name}</span>
                            <input type="hidden" name="product_name[]" value="${name}">
                        </td>
                        <td>
                            <span class="product-price">${formatRupiah(price)}</span>
                            <input type="hidden" name="selling_price[]" value="${price}">
                        </td>
                        <td>
                            <input type="number" name="qty[]" class="form-control qty" required
                                style="width: 70px; display: block; margin: 0 auto;"
                                min="0" max="${stock}" value="0">
                        </td>
                        <td>
                            <span class="product-stock">${stock}</span>
                        </td>
                        <td>
                            <span class="product-subtotal">0</span>
                            <input type="hidden" name="sub_total[]" class="subtotal" value="0">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                        </td>
                    </tr>
                `;

                document.getElementById('sales-details-body').insertAdjacentHTML('beforeend', newRow);
                updateRowNumbers();
            }

            calculateTotal();
        }

        document.body.addEventListener('click', function(e) {
            if (e.target.classList.contains('select-product')) {
                const barcode = e.target.dataset.barcode;
                const name = e.target.dataset.name;
                const price = parseFloat(e.target.dataset.price);
                const stock = parseInt(e.target.dataset.stock);

                addProductToTable(barcode, name, price, stock);
            }
        });

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#sales-details-body .row-number');
            rows.forEach((cell, index) => {
                cell.textContent = index + 1;
            });
        }

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
                updateRowNumbers();
                calculateTotal();
            }
        });

        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('qty')) {
                const row = e.target.closest('tr');
                const price = parseFloat(row.querySelector('input[name="selling_price[]"]').value) || 0;
                let qty = e.target.value.trim() === "" ? 0 : parseInt(e.target.value, 10);

                if (isNaN(qty) || qty < 0) {
                    qty = 0;
                }

                e.target.value = qty; // Pastikan nilai input tetap 0 jika kosong

                const subtotal = price * qty;

                row.querySelector('.product-subtotal').textContent = formatRupiah(subtotal);
                row.querySelector('.subtotal').value = subtotal;

                calculateTotal();
            }
        });


        // function calculateTotal() {
        //     let total = 0;
        //     document.querySelectorAll('.subtotal').forEach(input => {
        //         total += parseFloat(input.value) || 0;
        //     });

        //     const discount = total * 0.1;
        //     const ppn = (total - discount) * 0.11;
        //     const grandTotal = total - discount + ppn;

        //     document.getElementById('discount').value = formatRupiah(discount);
        //     document.getElementById('ppn').value = formatRupiah(ppn);
        //     document.getElementById('total_price').value = formatRupiah(grandTotal);
        // }
        document.getElementById('customer_id').addEventListener('change', calculateTotal);

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(input => {
                total += parseFloat(input.value) || 0;
            });

            const hasCustomer = document.getElementById('customer_id').value !== "";
            const discount = hasCustomer ? total * 0.1 : 0;
            const ppn = (total - discount) * 0.11;
            const grandTotal = total - discount + ppn;

            document.getElementById('discount').value = formatRupiah(discount);
            document.getElementById('ppn').value = formatRupiah(ppn);
            document.getElementById('total_price').value = formatRupiah(grandTotal);
        }

        document.getElementById('cash_paid').addEventListener('input', function() {
            const cashPaid = parseFloat(this.value) || 0;
            const grandTotal = parseFloat(document.getElementById('total_price').value.replace(/\D/g, '')) || 0;
            const cashReturn = Math.max(0, cashPaid - grandTotal);

            document.getElementById('cash_return').value = formatRupiah(cashReturn);
        });

        $(document).ready(function() {
            $('#customer_id').select2({
                placeholder: "Select Customer",
                allowClear: false,
                width: '100%'
            });
        });

        document.getElementById('add-product').addEventListener('click', function() {
            console.log('Choose Product button clicked'); // Debugging
            const modal = new bootstrap.Modal(document.getElementById('productModal'));
            modal.show();
        });
    </script>

@endsection
