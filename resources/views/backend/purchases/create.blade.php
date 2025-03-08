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
                <h1 class="h3 mb-0"><strong>Create</strong> Purchase</h1>
                <a href="{{ route('backend.purchases.index') }}" class="btn btn-secondary">Back to List</a>
            </div>

            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                    <form action="{{ route('backend.purchases.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>Transaction Date: {{ now()->format('d-m-Y') }}</h5>
                            </div>
                        </div>

                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="supplier_id" class="mb-2">Supplier</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
                            data-bs-target="#createSupplierModal">Create Supplier</button>

                        <h5>Purchase Details</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Barcode</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Stock</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="purchase-details-body"></tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="add-product">Choose Product</button>

                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="discount">Discount (10%)</label>
                                <input type="text" name="discount" id="discount" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="ppn">PPN (11%)</label>
                                <input type="text" name="ppn" id="ppn" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="total_price">Total</label>
                                <input type="text" name="total_price" id="total_price" class="form-control" readonly>
                            </div>
                        </div>

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
                    </form>
                </div>
            </div>
        </div>
    </main>

    @include('backend.purchases.modal-product')

    @include('backend.purchases.modal-supplier')

    <script>
        function formatRupiah(angka) {
            return '' + angka.toLocaleString('id-ID');
        }

        document.getElementById('add-product').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('productModal'));
            modal.show();
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('select-product')) {
                const id = e.target.dataset.id;
                const name = e.target.dataset.name;
                const price = parseFloat(e.target.dataset.price);
                const barcode = e.target.dataset.barcode;
                const stock = e.target.dataset.stock;

                const newRow = `
                    <tr>
                        <td class="row-number"></td>
                        <td>
                            <span class="product-barcode">${barcode}</span>
                        </td>
                        <td>
                            <span class="product-name">${name}</span>
                            <input type="hidden" name="product_name[]" value="${name}">
                        </td>
                        <td>
                            <span class="product-price">${formatRupiah(price)}</span>
                            <input type="hidden" name="purchase_price[]" value="${price}">
                        </td>
                        <td>
                            <input type="number" name="qty[]" class="form-control qty" required style="width: 100px; display: block; margin: 0 auto;" min="1">
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

                document.getElementById('purchase-details-body').insertAdjacentHTML('beforeend', newRow);
                updateRowNumbers();
                bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
            }
        });

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#purchase-details-body .row-number');
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
                const price = parseFloat(row.querySelector('input[name="purchase_price[]"]').value) || 0;
                const qty = parseInt(e.target.value) || 0;
                const subtotal = price * qty;

                row.querySelector('.product-subtotal').textContent = formatRupiah(subtotal);
                row.querySelector('.subtotal').value = subtotal;

                calculateTotal();
            }
        });

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(input => {
                total += parseFloat(input.value) || 0;
            });

            const discount = total * 0.1;
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

        document.querySelector('.btn-success.mb-3').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('createSupplierModal'));
            modal.show();
        });

        $(document).ready(function() {
            $('#supplier_id').select2({
                placeholder: "Select Supplier",
                allowClear: false,
                width: '100%'
            });
        });
    </script>
@endsection
