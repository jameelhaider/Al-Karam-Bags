@extends('dashboard.master2')
@section('admin_title', 'View Ledger | ' . $account->customer_name)
@section('content2')

    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-7 col-md-4 col-sm-5">
                    <a href="{{ url('admin/accounts') }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-9 col-5 col-md-8 col-sm-7">
                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-lg-block d-md-block" style="font-family: cursive;">
                            View Ledger | {{ $account->customer_name }}
                        </h3>
                        <h5 class="mt-1 d-block d-lg-none d-md-none" style="font-family: cursive;">
                            View Ledger | {{ $account->customer_name }}
                        </h5>
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
                background-color: #314861;
            }

            .custom-back-button i {
                font-size: 18px;
            }
        </style>




        <div class="card p-2 mb-0 mt-2">
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
                                    <td class="text-dark">{{ \Carbon\Carbon::parse($entry['date'])->format('d M y') }}</td>

                                    <td class="text-dark">{{ $entry['type'] }}</td>
                                    <td class="text-dark">
                                        @if ($entry['type'] === 'Sale Invoice')
                                            <a target="_BLANK" href="{{ route('invoice.view', $entry['id']) }}"
                                                class="btn btn-primary btn-xs">
                                               View | {{ $entry['type'] }} #{{ $entry['id'] }}
                                            </a>
                                        @elseif ($entry['type'] === 'Return Invoice')
                                            <a target="_BLANK" href="{{ route('invoice.return.view', $entry['id']) }}"
                                                class="btn text-white btn-xs" style="background-color: rgb(255, 162, 0);">
                                               View | {{ $entry['type'] }} #{{ $entry['id'] }}
                                            </a>
                                        @elseif ($entry['type'] === 'Cash Received')
                                            <a target="_BLANK" href="{{ route('cash.view.receipt', $entry['id']) }}"
                                                class="btn btn-xs text-white" style="background-color: rgb(1, 149, 1);">
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
                                <th class="text-end text-dark">{{ number_format($ledgerEntries->sum('debit'), 2) }}</th>
                                <th class="text-end text-dark">{{ number_format($ledgerEntries->sum('credit'), 2) }}</th>
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

@endsection
