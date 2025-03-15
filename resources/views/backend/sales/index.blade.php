@extends('backend.apps.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0"><strong>Sale</strong> Management</h1>
                <div>
                    <a href="javascript:void(0);" class="btn btn-secondary" onclick="printReceipt()">
                        Print <i data-feather="printer"></i>
                    </a>
                    {{-- <a href="javascript:void(0);" class="btn btn-secondary" onclick="exportPDF()">
                        Export to PDF <i data-feather="book"></i>
                    </a> --}}
                    <a href="{{ route('backend.sales.create') }}" class="btn btn-primary">
                        Create <i data-feather="plus"></i>
                    </a>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <select id="filterRange" class="form-select">
                        <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ request('filter') == 'yesterday' ? 'selected' : '' }}>Yesterday
                        </option>
                        <option value="this_week" {{ request('filter') == 'this_week' ? 'selected' : '' }}>This Week
                        </option>
                        <option value="last_week" {{ request('filter') == 'last_week' ? 'selected' : '' }}>Last Week
                        </option>
                        <option value="this_month" {{ request('filter') == 'this_month' ? 'selected' : '' }}>This Month
                        </option>
                        <option value="last_month" {{ request('filter') == 'last_month' ? 'selected' : '' }}>Last Month
                        </option>
                        <option value="this_month_last_year"
                            {{ request('filter') == 'this_month_last_year' ? 'selected' : '' }}>This Month Last Year
                        </option>
                        <option value="this_year" {{ request('filter') == 'this_year' ? 'selected' : '' }}>This Year
                        </option>
                        <option value="last_year" {{ request('filter') == 'last_year' ? 'selected' : '' }}>Last Year
                        </option>
                        <option value="time_span" {{ request('filter') == 'time_span' ? 'selected' : '' }}>Custom Range
                        </option>
                    </select>
                </div>
            </div>

            <div id="dateRangeContainer" style="display: none;" class="mb-3">
                <input type="date" id="startDate" class="form-control d-inline w-auto">
                <input type="date" id="endDate" class="form-control d-inline w-auto">
                <button onclick="applyDateFilter()" class="btn btn-primary">Apply</button>
            </div>

            <div class="card flex-fill p-3" id="printableArea">
                <div class="table-responsive">
                    <table class="table table-hover my-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>User</th>
                                <th>Total Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                    <td>{{ $sales->total() - ($sales->currentPage() - 1) * $sales->perPage() - $loop->index }}
                                    </td>
                                    <td>{{ $sale->created_at->translatedFormat('l, d M Y H:i:s') }}</td>
                                    <td>{{ $sale->customer->name ?? '-' }}</td>
                                    <td>{{ $sale->user->name }}</td>
                                    <td>{{ number_format($sale->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('backend.sales.show', $sale->id) }}" class="btn btn-sm btn-info">
                                            <i data-feather="eye"></i>
                                        </a>
                                        <form action="{{ route('backend.sales.destroy', $sale->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this sale?')">
                                                <i data-feather="trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $sales->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let filterSelect = document.getElementById('filterRange');
            let dateContainer = document.getElementById('dateRangeContainer');

            filterSelect.addEventListener('change', function() {
                if (this.value === 'time_span') {
                    dateContainer.style.display = 'block';
                } else {
                    dateContainer.style.display = 'none';
                    applyFilter(this.value);
                }
            });

            function applyFilter(filter) {
                window.location.href = "{{ route('backend.sales.index') }}?filter=" + filter;
            }

            window.applyDateFilter = function() {
                let startDate = document.getElementById('startDate').value;
                let endDate = document.getElementById('endDate').value;

                if (startDate && endDate) {
                    window.location.href = "{{ route('backend.sales.index') }}?filter=time_span&start_date=" +
                        startDate + "&end_date=" + endDate;
                }
            };
        });

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
