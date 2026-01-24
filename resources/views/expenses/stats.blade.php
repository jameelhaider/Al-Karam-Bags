@extends('dashboard.master2')
@section('admin_title', 'Admin | Profit Statistics')
@section('content2')
    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-6 col-md-3 col-sm-4">
                    <a href="{{ url('/admin') }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-home-circle me-2"></i> Dashboard
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-9 col-sm-8">
                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-lg-block d-md-block" style="font-family: cursive;">Profit Statistics</h3>
                        <h5 class="mt-1 d-block d-lg-none d-md-none" style="font-family: cursive;">Profit Statistics</h5>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .custom-back-button {
                font-size: 16px;
                height: 100%;
                width: 100%;
                border-radius: 0;
                text-decoration: none;
                transition: all 0.3s ease;
                font-weight: 500;
            }

            .custom-back-button:hover {
                background-color: #2596be;
                border: 0px;
            }

            .custom-back-button i {
                font-size: 18px;
            }
        </style>


        <div class="card mb-2 p-2 mt-2">

            <form action="" method="GET">
                <div class="row">
                    <div class="col-lg-9 col-md-8 col-sm-8 mt-1 mb-1">
                        <input type="month" class="form-control" value="{{ request()->month }}" name="month">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ url('admin/profit-stats') }}" title="Clear"
                                class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>

                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Charts Section -->
        @if ($sales != null)
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12 col-sm-12 mb-4">
                    <div class="card p-2" style="background:linear-gradient(135deg, rgba(105, 108, 255, 0.16), white)">
                        <canvas id="revenueChart"></canvas>
                    </div>

                </div>
                <div class="col-lg-6 col-md-6 col-12 col-sm-12 mb-4">
                    <div class="card p-2" style="background:linear-gradient(135deg, rgba(105, 108, 255, 0.16), white)">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
            </div>
        @endif


        @if ($sales != null)
            <div class="card shadow-sm p-4 mb-4" style="border-radius: 15px;">
                <div class="row mb-3">
                    <!-- Total Revenue This Month -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card text-center shadow-sm" style="background: rgb(255, 243, 191);border-radius: 8px;">
                            <div class="card-body">
                                <h5 class="card-title text-dark fw-bold">Total Revenue This Month</h5>
                                <h4 class="text-dark">{{ 'Rs. ' . number_format($totalSalesRevenue) }}</h4>
                            </div>
                        </div>
                    </div>
                    <!-- Total Expenses This Month -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card text-center shadow-sm" style="background: rgb(252, 190, 190); border-radius: 8px;">
                            <div class="card-body">
                                <h5 class="card-title text-dark fw-bold">Total Expenses This Month</h5>
                                <h4 class="text-dark">{{ 'Rs. ' . number_format($totalExpenses) }}</h4>
                            </div>
                        </div>
                    </div>
                    <!-- Total Profit This Month -->
                    <div class="col-lg-4 col-md-12 mb-3">
                        <div class="card text-center shadow-sm" style="background: rgb(178, 240, 178); border-radius: 8px;">
                            <div class="card-body">
                                <h5 class="card-title text-dark fw-bold">Total Profit This Month</h5>
                                <h4 class="text-dark">{{ 'Rs. ' . number_format($totalProfit) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- Additional Data Points (Optional) -->
                    <div class="col-lg-6 col-md-6 mb-3">
                        <div class="card text-center shadow-sm" style="background: #e0e7ff; border-radius: 8px;">
                            <div class="card-body">
                                <h5 class="card-title text-dark fw-bold">Total Sales Transactions</h5>
                                <h4 class="text-dark">{{ $totalSalesTransactions ?? 'N/A' }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 mb-3">
                        <div class="card text-center shadow-sm" style="background: #ffedd5;border-radius: 8px;">
                            <div class="card-body">
                                <h5 class="card-title text-dark fw-bold">Average Sale Value</h5>
                                <h4 class="text-dark">{{ 'Rs. ' . number_format($averageSaleValue ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif





        <div class="card p-3 mt-3">
            @if ($sales == null)
                <h3 class="text-center fw-bold text-dark">Stats will load once you search by month</h3>
            @endif

            <div class="row mt-4">
                @if ($expenses != null)
                    <div class="col-lg-6 col-md-6">
                        @if ($expenses->count() > 0)
                            <h4 class="fw-bold text-center text-dark">Expenses This Month ({{ $countexpenses }})</h4>
                            <div style="max-height: 400px;overflow:auto">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                                <th style="font-size:14px" class="text-dark fw-bold">Name</th>
                                                <th style="font-size:14px" class="text-dark fw-bold">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expenses as $key => $expense)
                                                <tr class="text-center">
                                                    <td class="text-dark">{{ ++$key }}</td>
                                                    <td class="text-dark">
                                                        @if ($expense->name)
                                                            {{ $expense->name }}
                                                        @else
                                                            {{ $expense->expense_type }}
                                                        @endif
                                                    </td>
                                                    <td class="text-dark">{{ 'Rs.' . number_format($expense->ammount) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="text-center">
                                                <td></td>
                                                <td class="fw-bold text-dark">Total Expenses</td>
                                                <td class="text-dark">{{ 'Rs.' . number_format($totalExpenses) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <h4 class="fw-bold text-center text-dark">Expenses This Month ({{ $countexpenses }})</h4>
                                <table class="table">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                            <th style="font-size:14px" class="text-dark fw-bold">Name</th>
                                            <th style="font-size:14px" class="text-dark fw-bold">Amount</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <h3 class="h3 text-center fw-normal text-muted mt-2">No Data Found!</h3>
                        @endif
                    </div>
                @endif

                @if ($sales != null)
                    <div class="col-lg-6 col-md-6">
                        @if ($sales->count() > 0)
                            <h4 class="fw-bold text-dark text-center">Invoices This Month ({{ $countsales }})</h4>
                            <div style="max-height: 400px;overflow:auto;">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                                <th style="font-size:14px" class="text-dark fw-bold">Invoice Id</th>
                                                <th style="font-size:14px" class="text-dark fw-bold">Profit</th>
                                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sales as $key => $sale)
                                                <tr class="text-center">
                                                    <td class="text-dark">{{ ++$key }}</td>
                                                    <td><a href="{{ route('invoice.view', ['id' => $sale->id]) }}"
                                                            class="text-dark">{{ $sale->invoice_id }}</a></td>
                                                    <td class="text-dark">{{ 'Rs.' . number_format($sale->profit) }}</td>
                                                    <td><a href="{{ route('invoice.view', ['id' => $sale->id]) }}"
                                                            class="btn btn-dark btn-sm" title="View Invoice"> Invoice <i
                                                                class="bx bx-show"></i>
                                                        </a></td>
                                                </tr>
                                            @endforeach
                                            <tr class="text-center">
                                                <td></td>
                                                <td class="text-dark fw-bold">Total Revenue</td>
                                                <td class="text-dark">{{ 'Rs.' . number_format($totalSalesRevenue) }}</td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <h4 class="fw-bold text-dark text-center">Invoices This Month ({{ $countsales }})</h4>
                                <table class="table">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                            <th style="font-size:14px" class="text-dark fw-bold">Invoice Id</th>
                                            <th style="font-size:14px" class="text-dark fw-bold">Profit</th>
                                            <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <h3 class="h3 text-center fw-normal text-muted">No Data Found!</h3>
                        @endif
                    </div>
                @endif
            </div>
        </div>




<div class="mt-5 container">
    <h4 class="mb-4 text-primary fw-bold text-center">Day-wise Report</h4>

    <!-- Download Button -->
    <div class="text-end mb-3">
        <button id="downloadReport" class="btn btn-sm btn-primary">
            Download Day-wise Report
        </button>
    </div>

    <div id="dayWiseTable" class="table-responsive shadow-sm rounded">
        <table class="table table-bordered table-striped table-hover align-middle mb-0">
            <thead class="table-primary text-center">
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Sale</th>
                    <th>Revenue</th>
                    <th>Expense</th>
                    <th>Profit</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dayWiseReport as $row)
                    <tr class="text-center">
                        <td class="text-dark">{{ $row['date'] }}</td>
                        <td class="text-dark">{{ $row['day'] }}</td>
                        <td class="fw-bold"
                            style="color: {{ $row['total_bill'] > 0 ? 'rgb(1, 149, 1)' : ($row['total_bill'] < 0 ? 'red' : 'gray') }}">
                            {{ 'Rs.' . number_format($row['total_bill']) }}
                        </td>

                        <td class="fw-bold"
                            style="color: {{ $row['revenue'] > 0 ? '#10559f' : ($row['revenue'] < 0 ? 'rgb(253, 27, 27)' : 'gray') }}">
                            {{ 'Rs.' . number_format($row['revenue']) }}
                        </td>

                        <td class="fw-bold"
                            style="color: {{ $row['expense'] > 0 ? 'rgb(255, 162, 0)' : ($row['expense'] < 0 ? 'rgb(253, 27, 27)' : 'gray') }}">
                            {{ 'Rs.' . number_format($row['expense']) }}
                        </td>

                        <td class="fw-bold"
                            style="color: {{ $row['profit'] > 0 ? 'rgb(1, 149, 1)' : ($row['profit'] < 0 ? 'rgb(253, 27, 27)' : 'gray') }}">
                            {{ 'Rs.' . number_format($row['profit']) }}
                        </td>

                        <td>
                            @if ($row['profit'] > 0)
                                <span class="badge" style="background-color: rgb(1, 149, 1)">In Profit</span>
                            @elseif($row['profit'] < 0)
                                <span class="badge" style="background-color: rgb(253, 27, 27)">In Loss</span>
                            @else
                                <span class="badge bg-secondary">No Profit No Loss</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted fw-bold py-4">No data available</td>
                    </tr>
                @endforelse
            </tbody>

            <!-- Footer for totals -->
            @php
                $report = collect($dayWiseReport);
            @endphp

            <tfoot class="table-primary text-center fw-bold">
                <tr>
                    <td colspan="2">Total</td>
                    <td style="color: rgb(1, 149, 1);font-size:18px" class="fw-bolder">
                        {{ 'Rs.' . number_format($report->sum('total_bill')) }}</td>
                    <td style="color: #10559f;font-size:18px" class="fw-bolder">
                        {{ 'Rs.' . number_format($report->sum('revenue')) }}</td>
                    <td style="color: rgb(255, 162, 0);font-size:18px" class="fw-bolder">
                        {{ 'Rs.' . number_format($report->sum('expense')) }}</td>
                    <td
                        style="font-size:18px;color: {{ $report->sum('profit') > 0 ? 'rgb(1, 149, 1)' : ($report->sum('profit') < 0 ? 'rgb(253, 27, 27)' : 'gray') }}">
                        {{ 'Rs.' . number_format($report->sum('profit')) }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- html2canvas JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.getElementById('downloadReport').addEventListener('click', function() {
        const table = document.getElementById('dayWiseTable');
        html2canvas(table).then(canvas => {
            const link = document.createElement('a');
            link.download = 'day_wise_report.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    });
</script>



        <style>
            body {
                background: #f4f7fa;
            }

            table thead th {
                letter-spacing: 0.5px;
            }

            .table-hover tbody tr:hover {
                background: #e9f5ff;
                transition: 0.3s;
            }
        </style>








    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: @json($sales ? $sales->pluck('invoice_id') : []), // Only pluck if $sales is not empty
                datasets: [{
                    label: 'Revenue',
                    data: @json($sales ? $sales->pluck('profit') : []), // Only pluck if $sales is not empty
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctxExpense = document.getElementById('expenseChart').getContext('2d');
        const expenseChart = new Chart(ctxExpense, {
            type: 'line',
            data: {
                labels: @json($expenses ? $expenses->pluck('expense_type') : []), // Only pluck if $expenses is not empty
                datasets: [{
                    label: 'Expenses',
                    data: @json($expenses ? $expenses->pluck('ammount') : []), // Only pluck if $expenses is not empty
                    fill: false,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

@endsection
