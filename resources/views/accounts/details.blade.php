@extends('dashboard.master2')
@php
    $title = 'View Details | ' . $account->customer_name;
@endphp
@section('admin_title', $title)
@section('content2')
    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a href="{{ url('admin/accounts') }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-6 col-sm-8">
                    <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">
                        {{ 'View Details | ' . $account->customer_name }}</h3>

                    <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                        {{ 'View Details | ' . $account->customer_name }}</h5>
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
                background-color: #314861;
            }

            .custom-back-button i {
                font-size: 18px;
            }
        </style>


        <style>
            .nav-tabs .nav-link.active {
                background-color: #f5f5f5 !important;
                color: rgb(117, 7, 14) !important;

            }

            .nav-tabs .nav-link {
                background-color: #f5f5f5 !important;
                color: rgb(117, 113, 113) !important;
                margin-bottom: 1px;
            }
        </style>



        <!-- Centered Tabs -->
        <ul class="nav nav-tabs justify-content-center mt-2" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details"
                    type="button" role="tab" aria-controls="details" aria-selected="true">Details</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sale-history-tab" data-bs-toggle="tab" data-bs-target="#sale-history"
                    type="button" role="tab" aria-controls="sale-history" aria-selected="false">Sale
                    History</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sale-invoices-tab" data-bs-toggle="tab" data-bs-target="#sale-invoices"
                    type="button" role="tab" aria-controls="sale-invoices" aria-selected="false">Sale
                    Invoices</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="return-history-tab" data-bs-toggle="tab" data-bs-target="#return-history"
                    type="button" role="tab" aria-controls="return-history" aria-selected="false">Return
                    History</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="return-invoices-tab" data-bs-toggle="tab" data-bs-target="#return-invoices"
                    type="button" role="tab" aria-controls="return-invoices" aria-selected="false">Return
                    Invoices</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cash-receiveds-tab" data-bs-toggle="tab" data-bs-target="#cash-receiveds"
                    type="button" role="tab" aria-controls="cash-receiveds" aria-selected="false">Cash
                    Receiveds</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="ledger-tab" data-bs-toggle="tab" data-bs-target="#ledger" type="button"
                    role="tab" aria-controls="ledger" aria-selected="false">Ledger</button>
            </li>
        </ul>

        <!-- Tabs Content inside Cards -->
        <div class="tab-content mt-1" id="myTabContent">

            {{-- Start Details Tab --}}
            <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                <div class="card shadow-sm p-3 rounded-0">
                    <h5 class="mb-3">Account Details</h5>

                    <div class="row g-3">
                        <!-- Account Info -->
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="border-bottom pb-2 mb-2">Account Information</h6>
                                <p><strong class="text-dark">Account ID:</strong> {{ $account->id }}</p>
                                <p><strong class="text-dark">Account Status:</strong>
                                    @if ($account->prev_balance > 0)
                                        <span class="badge" style="background-color: rgb(253, 27, 27)">
                                            Remainings
                                        </span>
                                    @elseif ($account->prev_balance == 0)
                                        <span class="badge" style="background-color: rgb(1, 149, 1)">
                                            Clear
                                        </span>
                                    @else
                                        <span class="badge" style="background-color: #6c757d">
                                            Credit
                                        </span>
                                    @endif
                                </p>




                                <p><strong class="text-dark">Current Balance:</strong>
                                    @if ($account->prev_balance > 0)
                                        <span class="fw-bold" style="color: rgb(253, 27, 27)">
                                            {{ ' Rs.' . number_format($account->prev_balance) }}
                                        </span>
                                    @elseif ($account->prev_balance == 0)
                                        <span class="fw-bold" style="color: rgb(1, 149, 1)">
                                            {{ ' Rs.' . number_format($account->prev_balance) }}
                                        </span>
                                    @else
                                        <span class="fw-bold" style="color: #6c757d">
                                            {{ ' Rs.' . number_format($account->prev_balance) }}
                                        </span>
                                    @endif
                                </p>
                                <p><strong class="text-dark">Customer Name:</strong> {{ $account->customer_name }}</p>
                                @if ($account->customer_address != null)
                                    <p><strong class="text-dark">Customer Address:</strong>
                                        {{ $account->customer_address }}</p>
                                @endif

                                <p><strong class="text-dark">Created Date:</strong>
                                    {{ \Carbon\Carbon::parse($account->created_at)->format('d M Y') }}</p>
                                @php
                                    $created = \Carbon\Carbon::parse($account->created_at);
                                    $now = \Carbon\Carbon::now();
                                    $diff = $created->diff($now);
                                @endphp

                                <p>
                                    <strong class="text-dark">Member Since:</strong>
                                    {{ $diff->y > 0 ? $diff->y . ' year' . ($diff->y > 1 ? 's ' : ' ') : '' }}
                                    {{ $diff->m > 0 ? $diff->m . ' month' . ($diff->m > 1 ? 's ' : ' ') : '' }}
                                    {{ $diff->d > 0 ? $diff->d . ' day' . ($diff->d > 1 ? 's ' : ' ') : '' }}
                                    ago
                                </p>


                                   <hr>
                                <h5 class="text-dark fw-bold">Closing Balance</h5>
                                <hr>

                                <!-- New Stats -->
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Debit: (Stock Sale Invoices)</span>
                                    <strong class="text-dark">
                                        {{ 'Rs. ' . number_format($saleinvoices->sum('total_bill') ?? 0) }}
                                    </strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Credit: (Cash Receiveds + Total Return)</span>
                                    @php
                                        $totalcredit = $cash->sum('ammount') + $returninvoices->sum('total_return');
                                    @endphp
                                    <strong class="text-dark">
                                        {{ 'Rs. ' . number_format($totalcredit ?? 0) }}
                                    </strong>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Current Balance:</span>
                                    <strong class="text-dark">
                                        {{ 'Rs. ' . number_format($account->prev_balance ?? 0) }}
                                    </strong>
                                </div>


                                 <hr>
                                <h5 class="text-dark fw-bold">Cash Receiveds</h5>
                                <hr>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Cash Receiveds:</span>
                                    <strong class="text-dark">{{ $cash->count() }} Times</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Nill Balance:</span>
                                    <strong class="text-dark">{{ $cash->where('final_rem', 0)->count() }} Times</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Not Nill Balance:</span>
                                    <strong class="text-dark">{{ $cash->where('final_rem', '>', 0)->count() }}
                                        Times</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Credit The Balance:</span>
                                    <strong class="text-dark">{{ $cash->where('final_rem', '<', 0)->count() }}
                                        Times</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Cash Receiveds:</span>
                                    @php
                                        $totalcashrec = $cash->sum('ammount');
                                    @endphp
                                    <strong class="text-dark">
                                        {{ 'Rs. ' . number_format($totalcashrec ?? 0) }}
                                    </strong>
                                </div>


                            </div>
                        </div>


                        @php
                            use Carbon\Carbon;
                            $totalSales = $saleinvoices->sum('total_bill') ?? 0;
                            $totalReturns = $returninvoices->sum('total_return') ?? 0;
                            $netSales = $totalSales - $totalReturns;
                            $totalProfit = $saleinvoices->sum('profit') ?? 0;
                            $firstSaleDate = $saleinvoices->min('created_at');
                            $lastSaleDate = $saleinvoices->max('created_at');
                            if ($firstSaleDate && $lastSaleDate) {
                                $totalDays =
                                    Carbon::parse($firstSaleDate)->diffInDays(Carbon::parse($lastSaleDate)) + 1;
                            } else {
                                $totalDays = 1;
                            }
                            $avgDaily = $totalDays > 0 ? $netSales / $totalDays : 0;
                            $avgWeekly = $netSales / ($totalDays / 7);
                            $avgMonthly = $netSales / ($totalDays / 30);
                            $avgDailyRevenue = $totalDays > 0 ? $totalProfit / $totalDays : 0;
                            $avgWeeklyRevenue = $totalProfit / ($totalDays / 7);
                            $avgMonthlyRevenue = $totalProfit / ($totalDays / 30);
                        @endphp
                        <!-- Stats Summary -->
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="border-bottom pb-2 mb-3">Account Statistics</h6>










                                <hr>
                                <h5 class="text-dark fw-bold">Sale Stats</h5>
                                <hr>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Sale Invoices:</span>
                                    <strong class="text-dark">{{ $saleinvoices->count() }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Sale Items:</span>
                                    <strong class="text-dark">{{ $saleitems->count() }}</strong>
                                </div>


                                <!-- Sales Overview -->
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Sale:</span>
                                    <strong class="text-dark">{{ 'Rs. ' . number_format($totalSales) }}</strong>
                                </div>





                                <!-- Average Sales -->
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Average Daily Sale:</span>
                                    <strong class="text-dark">{{ 'Rs. ' . number_format($avgDaily) }}</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Average Weekly Sale:</span>
                                    <strong class="text-dark">{{ 'Rs. ' . number_format($avgWeekly) }}</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Average Monthly Sale:</span>
                                    <strong class="text-dark">{{ 'Rs. ' . number_format($avgMonthly) }}</strong>
                                </div>
                                <hr>
                                <h5 class="text-dark fw-bold">Return Stats</h5>
                                <hr>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Return Invoices:</span>
                                    <strong class="text-dark">{{ $returninvoices->count() }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Return Items:</span>
                                    <strong class="text-dark">{{ $return_items->count() }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Return:</span>
                                    <strong class="text-dark">{{ 'Rs. ' . number_format($totalReturns) }}</strong>
                                </div>


                                <hr>
                                <h5 class="text-dark fw-bold mt-3">Revenue Stats</h5>
                                <hr>

                                <!-- Revenue (Profit) Stats -->
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Revenue (Profit):</span>
                                    <strong class="text-dark">{{ 'Rs. ' . number_format($totalProfit) }}</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Average Daily Revenue:</span>
                                    <strong class="text-dark">{{ 'Rs. ' . number_format($avgDailyRevenue) }}</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Average Weekly Revenue:</span>
                                    <strong class="text-dark">{{ 'Rs. ' . number_format($avgWeeklyRevenue) }}</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <span>Average Monthly Revenue:</span>
                                    <strong class="text-dark">{{ 'Rs. ' . number_format($avgMonthlyRevenue) }}</strong>
                                </div>






                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Details Tab --}}


            {{-- Start Sale History Tab --}}
            <div class="tab-pane fade" id="sale-history" role="tabpanel" aria-labelledby="sale-history-tab">
                <div class="card shadow-sm p-2 rounded-0">
                    @if ($saleitems->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">#</th>
                                        <th style="font-size:14px;width:35%" class="text-dark fw-bold">Name
                                        </th>
                                        <th style="font-size:14px;width:10%" class="text-dark text-center fw-bold">Price
                                        </th>
                                        <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">Qty
                                        </th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Total
                                        </th>
                                        <th style="font-size:14px;width:10%" class="text-dark fw-bold">No | View
                                        </th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($saleitems as $key => $item)
                                        <tr>
                                            <td class="text-dark text-center">{{ ++$key }}</td>

                                            <td class="text-dark fw-bold" style="font-size: 13px">
                                                {{ $item->item_name }}
                                            </td>

                                            <td class="text-dark text-center">
                                                {{ 'Rs.' . number_format($item->item_price) }}
                                            </td>
                                            <td class="text-dark text-center">
                                                {{ number_format($item->item_qty) }}
                                            </td>

                                            <td class="text-dark text-center">
                                                {{ 'Rs.' . number_format($item->item_total) }}
                                            </td>
                                            <td>
                                                @if ($item->stock_id != null)
                                                    <a href="{{ route('invoice.view', ['id' => $item->invoice_no]) }}"
                                                        target="_Blank" class="btn btn-xs btn-primary mt-1">
                                                        {{ $item->invoice_no }} | View</a>
                                                @else
                                                    --------
                                                @endif

                                            </td>

                                            <td class="text-dark text-center">
                                                {{ \Carbon\Carbon::parse($item->sold_date)->format('d M y') }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">#</th>
                                        <th style="font-size:14px;width:35%" class="text-dark fw-bold">Name
                                        </th>
                                        <th style="font-size:14px;width:10%" class="text-dark text-center fw-bold">Price
                                        </th>
                                        <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">Qty
                                        </th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Total
                                        </th>
                                        <th style="font-size:14px;width:10%" class="text-dark fw-bold">No | View
                                        </th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Date
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <h4 class="h4 text-center fw-normal text-muted mt-2">No Data Found!</h4>
                    @endif
                </div>
            </div>
            {{-- End Sale History Tab --}}


            {{-- Start Sale Invoices Tab --}}
            <div class="tab-pane fade" id="sale-invoices" role="tabpanel" aria-labelledby="sale-invoices-tab">
                <div class="card shadow-sm p-2 rounded-0">
                    @if ($saleinvoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Invoice ID</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Invoice No</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Total Bill</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Total Items</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Date</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($saleinvoices as $key => $invoice)
                                        <tr class="text-center">
                                            <td class="text-dark">{{ ++$key }}</td>
                                            <td title="{{ $invoice->invoice_id }}">
                                                <a class="text-dark"
                                                    href="{{ route('invoice.view', ['id' => $invoice->id]) }}">
                                                    {{ $invoice->invoice_id }}

                                                    @if ($invoice->status == 'Returned')
                                                        <p class="mb-0 fw-light"
                                                            style="font-size: 12px;color:rgb(253, 27, 27)">
                                                            {{ $invoice->status }}</p>
                                                    @endif
                                                </a>
                                            <td class="text-dark" title="{{ $invoice->id }}">
                                                {{ $invoice->id }}
                                                <i class="bx bx-copy"
                                                    style="color: #2596be;font-size:14px;cursor: pointer;"
                                                    onclick="copyToClipboard(this, '{{ $invoice->id }}')">
                                                </i>
                                                <span class="copied-msg"
                                                    style="display:none; color: green; font-size: 12px; margin-left: 5px;">Copied!</span>
                                            </td>
                                            <script>
                                                function copyToClipboard(iconElement, text) {
                                                    navigator.clipboard.writeText(text).then(() => {
                                                        const copiedMsg = iconElement.nextElementSibling;
                                                        copiedMsg.style.display = 'inline';

                                                        setTimeout(() => {
                                                            copiedMsg.style.display = 'none';
                                                        }, 1500);
                                                    }).catch(err => {
                                                        console.error('Failed to copy text: ', err);
                                                    });
                                                }
                                            </script>
                                            <td title="{{ 'Rs.' . number_format($invoice->total_bill) }}"> <a
                                                    class="text-dark"
                                                    href="{{ route('invoice.view', ['id' => $invoice->id]) }}">
                                                    {{ 'Rs.' . number_format($invoice->total_bill) }}
                                                </a>
                                            </td>
                                            <td title="{{ $invoice->total_items }}"> <a class="nav-link"
                                                    href="{{ route('invoice.view', ['id' => $invoice->id]) }}">
                                                    <span class="badge bg-primary">{{ $invoice->total_items }}</span>
                                                </a>
                                            </td>

                                            <td class="text-dark"
                                                title="{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M y') }}">
                                                {{ \Carbon\Carbon::parse($invoice->created_at)->format('d M y') }}
                                            </td>


                                            <td>

                                                <div class="dropdown ms-auto">
                                                    <button class="btn btn-dark btn-sm dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <li>
                                                            <a class="dropdown-item" target="_BLANK"
                                                                href="{{ route('invoice.view', ['id' => $invoice->id]) }}">View
                                                                Invoice</a>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Invoice ID</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Invoice No</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Total Bill</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Total Items</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Date</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <h4 class="h4 mt-2 text-center text-dark fw-bold">No Data Found!</h4>
                    @endif

                </div>
            </div>
            {{-- End Sale Invoices Tab --}}

            {{-- Start Return History Tab --}}
            <div class="tab-pane fade" id="return-history" role="tabpanel" aria-labelledby="return-history-tab">
                <div class="card shadow-sm p-2 rounded-0">
                    @if ($return_items->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">#</th>
                                        <th style="font-size:14px;width:35%" class="text-dark fw-bold">Name
                                        </th>
                                        <th style="font-size:14px;width:15%" class="text-dark text-center fw-bold">Price
                                        </th>
                                        <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">Qty
                                        </th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Total
                                        </th>
                                        <th style="font-size:14px;width:10%" class="text-dark fw-bold">ID | View
                                        </th>

                                        <th style="font-size:14px;width:12%" class="text-dark fw-bold text-center">Return
                                            Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($return_items as $key => $item)
                                        <tr>
                                            <td class="text-dark text-center">{{ ++$key }}</td>

                                            <td class="text-dark fw-bold" style="font-size: 14px">
                                                {{ $item->item_name }}
                                            </td>

                                            <td class="text-dark text-center">
                                                {{ 'Rs.' . number_format($item->item_price) }}
                                            </td>
                                            <td class="text-dark text-center">
                                                {{ number_format($item->qty) }}
                                            </td>

                                            <td class="text-dark text-center">
                                                {{ 'Rs.' . number_format($item->total) }}
                                            </td>
                                            <td class="text-dark fw-bold">
                                                @if ($item->invoice_id)
                                                    <a href="{{ url('admin/invoices/view/return/' . $item->invoice_id) }}"
                                                        target="_BLANK"
                                                        class="btn btn-xs btn-primary">{{ $item->invoice_id }} | View</a>
                                                @else
                                                    --------
                                                @endif
                                            </td>

                                            <td class="text-dark text-center">
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M y') }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">#</th>
                                        <th style="font-size:14px;width:35%" class="text-dark fw-bold">Name
                                        </th>
                                        <th style="font-size:14px;width:15%" class="text-dark text-center fw-bold">Price
                                        </th>
                                        <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">Qty
                                        </th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Total
                                        </th>
                                        <th style="font-size:14px;width:10%" class="text-dark fw-bold">ID | View
                                        </th>

                                        <th style="font-size:14px;width:12%" class="text-dark fw-bold text-center">Return
                                            Date</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <h4 class="h4 text-center fw-normal text-muted mt-2">No Data Found!</h4>
                    @endif
                </div>
            </div>
            {{-- End Return History Tab --}}

            {{-- Start Return Invoices Tab --}}
            <div class="tab-pane fade" id="return-invoices" role="tabpanel" aria-labelledby="return-invoices-tab">
                <div class="card shadow-sm p-2 rounded-0">
                    @if ($returninvoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Invoice ID</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Invoice No</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Total Return</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Total Items</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Date</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($returninvoices as $key => $invoice)
                                        <tr class="text-center">
                                            <td class="text-dark">{{ ++$key }}</td>
                                            <td title="{{ $invoice->invoice_id }}">
                                                <a class="text-dark" target="_BLANK"
                                                    href="{{ route('invoice.return.view', ['id' => $invoice->id]) }}">
                                                    {{ $invoice->invoice_id }}
                                                </a>
                                            <td class="text-dark" title="{{ $invoice->id }}">
                                                {{ $invoice->id }}
                                                <i class="bx bx-copy"
                                                    style="color: #2596be;font-size:14px;cursor: pointer;"
                                                    onclick="copyToClipboard(this, '{{ $invoice->id }}')">
                                                </i>
                                                <span class="copied-msg"
                                                    style="display:none; color: green; font-size: 12px; margin-left: 5px;">Copied!</span>
                                            </td>
                                            <script>
                                                function copyToClipboard(iconElement, text) {
                                                    navigator.clipboard.writeText(text).then(() => {
                                                        const copiedMsg = iconElement.nextElementSibling;
                                                        copiedMsg.style.display = 'inline';

                                                        setTimeout(() => {
                                                            copiedMsg.style.display = 'none';
                                                        }, 1500);
                                                    }).catch(err => {
                                                        console.error('Failed to copy text: ', err);
                                                    });
                                                }
                                            </script>





                                            <td title="{{ 'Rs.' . number_format($invoice->total_return) }}"> <a
                                                    class="text-dark"
                                                    href="{{ route('invoice.view', ['id' => $invoice->id]) }}">
                                                    {{ 'Rs.' . number_format($invoice->total_return) }}
                                                </a>
                                            </td>
                                            <td title="{{ $invoice->total_items }}"> <a class="nav-link"
                                                    href="{{ route('invoice.view', ['id' => $invoice->id]) }}">
                                                    <span class="badge bg-primary">{{ $invoice->total_items }}</span>
                                                </a>
                                            </td>

                                            <td class="text-dark"
                                                title="{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M y') }}">
                                                {{ \Carbon\Carbon::parse($invoice->created_at)->format('d M y') }}
                                            </td>


                                            <td>

                                                <div class="dropdown ms-auto">
                                                    <button class="btn btn-dark btn-sm dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <li>
                                                            <a class="dropdown-item" target="_BLANK"
                                                                href="{{ route('invoice.return.view', ['id' => $invoice->id]) }}">View
                                                                Invoice</a>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Invoice ID</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Invoice No</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Total Return</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Total Items</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Date</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <h4 class="h4 mt-2 text-center text-dark fw-bold">No Data Found!</h4>
                    @endif
                </div>
            </div>
            {{-- End Return Invoices Tab --}}

            {{-- Start Cash Receiveds Tab --}}
            <div class="tab-pane fade" id="cash-receiveds" role="tabpanel" aria-labelledby="cash-receiveds-tab">
                <div class="card shadow-sm p-2 rounded-0">
                    @if ($cash->count() > 0)
                        <form action="{{ route('cash.received.download.pdf') }}" method="post">
                            @csrf
                            <input type="hidden" name="account_id" value="{{ $account->id }}">
                            <input type="submit" value="Download PDF" class="btn btn-primary float-end mt-2">
                        </form>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">#</th>
                                        <th style="font-size:14px;width:28%" class="text-dark fw-bold">Customer Name</th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">
                                            Narration</th>
                                        <th style="font-size:14px;width:20%" class="text-dark fw-bold text-center">Amount
                                            Received
                                        </th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">R.A.R</th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Date
                                        </th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cash as $key => $csh)
                                        <tr>
                                            <td class="text-dark text-center">{{ ++$key }}</td>
                                            <td>
                                                <a class="text-dark fw-bold"
                                                    href="{{ route('cash.view.receipt', ['id' => $csh->id]) }}">
                                                    {{ $csh->customer_name }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a class="text-dark" href="{{ route('cash.edit', ['id' => $csh->id]) }}">
                                                    {{ $csh->narration }}
                                                </a>
                                            </td>
                                            <td class="text-dark text-center fw-bold">
                                                {{ 'Rs.' . number_format($csh->ammount) }}
                                            </td>
                                            <td class="text-dark text-center">
                                                @if ($csh->final_rem > 0)
                                                    <span class="fw-bolder" style="color: rgb(253, 27, 27);">
                                                        {{ 'Rs.' . number_format($csh->final_rem) }}</span>
                                                @elseif ($csh->final_rem < 0)
                                                    <span class="fw-bolder" style="color: #6c757d;">
                                                        {{ 'Rs.' . number_format($csh->final_rem) }}</span>
                                                @else
                                                    <span class="fw-bolder" style="color: rgb(1, 149, 1);">
                                                        {{ 'Rs.' . number_format($csh->final_rem) }}</span>
                                                @endif

                                            </td>

                                            <td class="text-dark text-center">
                                                {{ \Carbon\Carbon::parse($csh->created_at)->format('d M y') }}
                                            </td>





                                            <td>
                                                <div class="dropdown ms-auto">
                                                    <button class="btn btn-dark btn-sm dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="dropdownMenuButton">
                                                        <li>
                                                            <a class="dropdown-item" target="_Blank"
                                                                href="{{ route('cash.view.receipt', ['id' => $csh->id]) }}">View
                                                                Receipt</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" target="_Blank"
                                                                href="{{ route('cash.edit', ['id' => $csh->id]) }}">Edit</a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">#</th>
                                        <th style="font-size:14px;width:28%" class="text-dark fw-bold">Customer Name</th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">
                                            Narration</th>
                                        <th style="font-size:14px;width:20%" class="text-dark fw-bold text-center">Amount
                                            Received
                                        </th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">R.A.R</th>
                                        <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Date
                                        </th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <h4 class="h4 text-center fw-normal text-muted mt-2">No Data Found!</h4>
                    @endif
                </div>
            </div>
            {{-- End Cash Receiveds Tab --}}

            {{-- Start Ledger Tab --}}
            <div class="tab-pane fade" id="ledger" role="tabpanel" aria-labelledby="ledger-tab">
                <div class="card shadow-sm p-2 rounded-0">
                    @if ($ledgerEntries->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px;" class="text-dark fw-bold text-center">#</th>
                                        <th style="font-size:14px;" class="text-dark fw-bold">Date
                                        </th>
                                        <th style="font-size:14px;" class="text-dark fw-bold">Type
                                        </th>
                                        <th style="font-size:14px;" class="text-dark fw-bold">View | Description</th>
                                        <th style="font-size:14px;" class="text-dark fw-bold text-end">Debit
                                        </th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-end">Credit</th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-end">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $balance = 0; @endphp
                                    @foreach ($ledgerEntries as $key => $entry)
                                        @php
                                            $balance += $entry['debit'];
                                            $balance -= $entry['credit'];
                                        @endphp
                                        <tr>
                                            <td class="text-center text-dark">{{ ++$key }}</td>
                                            <td class="text-dark">
                                                {{ \Carbon\Carbon::parse($entry['date'])->format('d M y') }}</td>

                                            <td class="text-dark">{{ $entry['type'] }}</td>
                                            <td class="text-dark">
                                                @if ($entry['type'] === 'Sale Invoice')
                                                    <a target="_BLANK" href="{{ route('invoice.view', $entry['id']) }}"
                                                        class="btn btn-primary btn-xs">
                                                        View | {{ $entry['type'] }} #{{ $entry['id'] }}
                                                    </a>
                                                @elseif ($entry['type'] === 'Return Invoice')
                                                    <a target="_BLANK"
                                                        href="{{ route('invoice.return.view', $entry['id']) }}"
                                                        class="btn text-white btn-xs"
                                                        style="background-color: rgb(255, 162, 0);">
                                                        View | {{ $entry['type'] }} #{{ $entry['id'] }}
                                                    </a>
                                                @elseif ($entry['type'] === 'Cash Received')
                                                    <a target="_BLANK"
                                                        href="{{ route('cash.view.receipt', $entry['id']) }}"
                                                        class="btn btn-xs text-white"
                                                        style="background-color: rgb(1, 149, 1);">
                                                        View | {{ $entry['type'] }} #{{ $entry['id'] }}
                                                    </a>
                                                @endif
                                            </td>

                                            <td class="text-end text-dark">{{ number_format($entry['debit'], 2) }}</td>
                                            <td class="text-end text-dark">{{ number_format($entry['credit'], 2) }}</td>
                                            <td class="text-end text-dark">{{ number_format($balance, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th colspan="4" class="text-end fw-bold text-dark">Closing Balance</th>
                                        <th class="text-end text-dark">
                                            {{ number_format($ledgerEntries->sum('debit'), 2) }}</th>
                                        <th class="text-end text-dark">
                                            {{ number_format($ledgerEntries->sum('credit'), 2) }}</th>
                                        <th class="text-end text-dark">{{ number_format($balance, 2) }}</th>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px;" class="text-dark fw-bold text-center">#</th>
                                        <th style="font-size:14px;" class="text-dark fw-bold">Date
                                        </th>
                                        <th style="font-size:14px;" class="text-dark fw-bold">Type
                                        </th>
                                        <th style="font-size:14px;" class="text-dark fw-bold">View | Description</th>
                                        <th style="font-size:14px;" class="text-dark fw-bold text-center">Debit
                                        </th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">Credit</th>
                                        <th style="font-size:14px" class="text-dark fw-bold text-center">Balance</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <h4 class="h4 text-center fw-normal text-muted mt-2">No Data Found!</h4>
                    @endif
                </div>
            </div>
            {{-- End Ledger Tab --}}
        </div>








    </div>


@endsection
