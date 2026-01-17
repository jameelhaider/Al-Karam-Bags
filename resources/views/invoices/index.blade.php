@extends('components.invoicetabs')
@section('admin_title', 'Admin | List Sale Invoices')
@section('content4')
    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-7 col-md-4 col-sm-5">
                    <a href="{{ url('/admin/invoice/make') }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-plus me-1"></i> Make Sale Invoice
                    </a>
                </div>
                <div class="col-lg-9 col-5 col-md-8 col-sm-7">

                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family: cursive;">List Sale Invoices</h3>
                        <h5 class="mt-1 d-none d-sm-block d-md-none" style="font-family: cursive;">List Sale Invoices</h5>
                        <small class="mt-1 d-block d-sm-none d-lg-none d-md-none" style="font-family: cursive;">List Sale
                            Invoices</small>


                        <div class="form-check form-switch ms-4 d-none d-lg-block">
                            <input class="form-check-input" type="checkbox" id="toggleProfit" onclick="toggleProfitField()"
                                style="cursor: pointer">
                            <label class="form-check-label" for="toggleProfit" style="cursor: pointer">Show Profit</label>
                        </div>


                        <script>
                            function toggleProfitField() {
                                const toggleSwitch = document.getElementById('toggleProfit');
                                const isChecked = toggleSwitch.checked;
                                localStorage.setItem('showProfit', isChecked);
                                updatePriceVisibility(isChecked);
                            }

                            function updatePriceVisibility(isVisible) {
                                const priceHeader = document.querySelector('.profit-header');
                                const priceCells = document.querySelectorAll('.profit-cell');
                                if (isVisible) {
                                    priceHeader.style.display = '';
                                    priceCells.forEach(cell => cell.style.display = '');
                                } else {
                                    priceHeader.style.display = 'none';
                                    priceCells.forEach(cell => cell.style.display = 'none');
                                }
                            }
                            document.addEventListener('DOMContentLoaded', () => {
                                const savedState = localStorage.getItem('showProfit');
                                const isVisible = savedState === 'true';
                                const toggleSwitch = document.getElementById('toggleProfit');
                                toggleSwitch.checked = isVisible;
                                updatePriceVisibility(isVisible);
                            });
                        </script>

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
                    <div class="col-lg-3 col-md-4 col-sm-4 col-6 mt-1 mb-1">

                        <select name="account_id" class="form-select" id="acc-select" onchange="this.form.submit()">
                            <option value="{{ null }}">Select Account</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ request()->account_id == $account->id ? 'selected' : '' }}>
                                    {{ $account->id . ')' . $account->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-4 col-6 mt-1 mb-1">
                        <input type="text" value="{{ request()->invoice_no }}" name="invoice_no" placeholder="Invoice No"
                            class="form-control" type="number">
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-4 col-6 mt-1 mb-1">

                        <input type="text" value="{{ request()->invoice_id }}" name="invoice_id"
                            placeholder="CC-000000000" class="form-control" data-inputmask="'mask': 'CC-999999999'"
                            type="number" maxlength = "12">

                    </div>



                    <div class="col-lg-2 col-md-6 col-sm-6 col-6 mt-1 mb-1">
                        <input type="date" class="form-control" value="{{ request()->date }}" name="date">
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6 col-12 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ url('admin/invoices') }}" title="Clear" class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>


                        </div>
                    </div>
                </div>
            </form>
        </div>


        @if ($invoices->count() > 0 && (request()->invoice_id || request()->date || request()->account_id))
            <div class="alert bg-primary text-white mt-3">
                <strong>{{ $invoices->count() }}
                    {{ $invoices->count() > 0 && $invoices->count() < 2 ? 'Result' : 'Results' }}
                    Found</strong>
            </div>
        @elseif ($invoices->count() < 1 && (request()->invoice_id || request()->date || request()->account_id))
            <div class="alert bg-warning text-white mt-3">
                <strong>No Results Found !</strong>
            </div>
        @endif


        <div class="card p-2 mb-0">
            @if ($invoices->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Invoice ID</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Invoice No</th>
                                <th style="font-size:14px" class="text-dark fw-bold profit-header">Profit</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Total Bill</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Total Items</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Date</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $key => $invoice)
                                <tr class="text-center">
                                    <td class="text-dark">{{ ++$key }}</td>
                                    <td title="{{ $invoice->invoice_id }}">
                                        <a class="text-dark" target="_BLANK" href="{{ route('invoice.view', ['id' => $invoice->id]) }}">
                                            {{ $invoice->invoice_id }}
                                            <p class="mb-0 fw-bold" style="font-size: 12px"> {{ $invoice->invoice_to }}</p>

                                            @if ($invoice->status == 'Returned')
                                                <p class="mb-0 fw-light" style="font-size: 12px;color:rgb(253, 27, 27)">
                                                    {{ $invoice->status }}</p>
                                            @endif
                                        </a>
                                    <td class="text-dark" title="{{ $invoice->id }}">
                                        {{ $invoice->id }}
                                        <i class="bx bx-copy" style="color: #2596be;font-size:14px;cursor: pointer;"
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
                                                }, 1500); // message disappears after 1.5 seconds
                                            }).catch(err => {
                                                console.error('Failed to copy text: ', err);
                                            });
                                        }
                                    </script>



                                    <td title="{{ 'Rs.' . number_format($invoice->profit) }}" class="profit-cell"> <a
                                            class="text-dark" href="{{ route('invoice.view', ['id' => $invoice->id]) }}">
                                            {{ 'Rs.' . number_format($invoice->profit) }}
                                        </a>
                                    </td>


                                    <td title="{{ 'Rs.' . number_format($invoice->total_bill) }}"> <a class="text-dark"
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
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
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
                    <div class="float-end mt-2">
                        {{ $invoices->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Invoice Id</th>
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


    <script>
        $(":input").inputmask();
    </script>


    <style>
        .select2-container--default .select2-selection--single {
            display: block;
            width: 100%;
            padding: 0.300rem 0.200rem 0.300rem 0.200rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            height: auto;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50%;
            right: 0.32rem;
            transform: translateY(-50%);
            height: auto;
        }
    </style>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script>
        $('#acc-select').select2({
            placeholder: 'Select Account',
            allowClear: true
        });
    </script>


@endsection
