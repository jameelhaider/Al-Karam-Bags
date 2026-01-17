@extends('dashboard.master2')
@section('admin_title', 'Admin | Accounts')
@section('content2')

    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-7 col-md-4 col-sm-5">
                    <a href="{{ route('create.account') }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-plus me-1"></i> Create New Account
                    </a>
                </div>
                <div class="col-lg-9 col-5 col-md-8 col-sm-7 ">

                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-lg-block d-md-block" style="font-family: cursive;">Accounts

                        </h3>
                        <h5 class="mt-1 d-block d-lg-none d-md-none" style="font-family: cursive;">Accounts</h5>



                        <div class="form-check form-switch ms-4 d-none d-lg-block d-md-block">
                            <input class="form-check-input" type="checkbox" id="toggleRemBalance"
                                onclick="toggleRemBalance()" style="cursor: pointer">
                            <label class="form-check-label" for="toggleRemBalance" style="cursor: pointer">Show Rem
                                Balance</label>
                        </div>

                        <div id="show" class="d-none">
                            <h3 class="mt-1 d-none d-lg-block d-md-block ms-2" style="font-family: cursive;">
                                {{ 'Rs.' . number_format($totalRem) }}</h3>
                        </div>


                        <script>
                            // Set state on toggle
                            function toggleRemBalance() {
                                const toggleSwitch = document.getElementById('toggleRemBalance');
                                const showElement = document.getElementById('show');

                                if (toggleSwitch.checked) {
                                    showElement.classList.remove('d-none');
                                    showElement.classList.add('d-block');
                                    localStorage.setItem('showRemBalance', 'true');
                                } else {
                                    showElement.classList.remove('d-block');
                                    showElement.classList.add('d-none');
                                    localStorage.setItem('showRemBalance', 'false');
                                }
                            }

                            // Load saved state on page load
                            window.addEventListener('DOMContentLoaded', () => {
                                const toggleSwitch = document.getElementById('toggleRemBalance');
                                const showElement = document.getElementById('show');
                                const savedState = localStorage.getItem('showRemBalance');

                                if (savedState === 'true') {
                                    toggleSwitch.checked = true;
                                    showElement.classList.remove('d-none');
                                    showElement.classList.add('d-block');
                                } else {
                                    toggleSwitch.checked = false;
                                    showElement.classList.remove('d-block');
                                    showElement.classList.add('d-none');
                                }
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
                    <div class="col-lg-2 col-md-1 col-sm-6 col-6 mt-1 mb-1">
                        <input type="number" min="1" class="form-control" value="{{ request()->acc_id }}"
                            placeholder="Acccount ID" name="acc_id">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                        <input type="text" class="form-control" value="{{ request()->name }}" placeholder="Customer Name"
                            name="name">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-4 mt-1 mb-1">
                        <input type="text" value="{{ request()->phone }}" name="phone" placeholder="0300-0000000"
                            class="form-control" data-inputmask="'mask': '0399-9999999'" type="number" maxlength = "12">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4 col-4 mt-1 mb-1">
                        <select name="status" class="form-select" onchange="this.form.submit()" id="">
                            <option value="">All</option>
                            <option value="Clear" {{ request()->status == 'Clear' ? 'selected' : '' }}>Clear</option>
                            <option value="Credit" {{ request()->status == 'Credit' ? 'selected' : '' }}>Credit</option>
                            <option value="Remainings" {{ request()->status == 'Remainings' ? 'selected' : '' }}>Remainings
                            </option>
                        </select>
                    </div>


                    <div class="col-lg-2 col-md-3 col-sm-4 col-4 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ url('admin/accounts') }}" title="Clear" class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        @if ($accounts->count() > 0 && (request()->name || request()->phone || request()->acc_id || request()->status))
            <div class="alert bg-primary text-white mt-3">
                <strong>{{ $accounts->count() }}
                    {{ $accounts->count() > 0 && $accounts->count() < 2 ? 'Result' : 'Results' }}
                    Found</strong>
            </div>
        @elseif ($accounts->count() < 1 && (request()->name || request()->phone || request()->acc_id || request()->status))
            <div class="alert bg-warning text-white mt-3">
                <strong>No Results Found !</strong>
            </div>
        @endif


        <div class="card p-2 mb-0">
            @if ($accounts->count() > 0)
                <div class="d-flex justify-content-end">
                    <form action="{{ route('accounts.download.pdf') }}" method="post">
                        @csrf
                        <input type="hidden" name="status" value="Both">
                        <input type="hidden" name="account_id" value="All">
                        <input type="submit" value="Download PDF" class="w-100 btn btn-primary mt-2">
                    </form>
                </div>


                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-size:14px;width:10%" class="text-dark fw-bold text-center">Acc ID</th>
                                <th style="font-size:14px;width:26%" class="text-dark fw-bold">Customer Name
                                </th>
                                <th style="font-size:14px;width:17%" class="text-dark fw-bold text-center">Phone
                                </th>
                                <th style="font-size:14px;width:25%" class="text-dark fw-bold">Address</th>
                                <th style="font-size:14px;width:20%" class="text-dark fw-bold text-center">Balance
                                </th>
                                <th style="font-size:14px" class="text-dark fw-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $key => $account)
                                <tr>
                                    <td class="text-dark text-center">{{ $account->id }}</td>
                                    <td>
                                        <a class="text-dark fw-bold" target="_Blank"
                                            href="{{ route('account.ledger', ['id' => $account->id]) }}">
                                            {{ $account->customer_name }}
                                            <br>
                                            @if ($account->prev_balance > 0)
                                                <span class="badge rounded-0"
                                                    style="background-color: rgb(253, 27, 27);font-size:9px">REMAININGS</span>
                                            @elseif ($account->prev_balance < 0)
                                                <span class="badge rounded-0"
                                                    style="font-size:9px;background-color:#6c757d">CREDIT</span>
                                            @else
                                                <span class="badge rounded-0"
                                                    style="background-color: rgb(1, 149, 1);font-size:9px;">CLEAR</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a class="text-dark" href="{{ route('account.edit', ['id' => $account->id]) }}">
                                            {{ $account->customer_phone ? $account->customer_phone : '---------' }}
                                        </a>
                                    </td>
                                    <td class="text-dark" style="font-size: 13px">
                                        {{ $account->customer_address ? $account->customer_address : '---------' }}
                                    </td>

                                    <td class="text-dark text-center">
                                        @if ($account->prev_balance > 0)
                                            <span class="fw-bolder balance positive">
                                                {{ 'Rs.' . number_format($account->prev_balance) }}
                                            </span>
                                        @elseif ($account->prev_balance < 0)
                                            <span class="fw-bolder balance neutral">
                                                {{ 'Rs.' . number_format($account->prev_balance) }}
                                            </span>
                                        @else
                                            <span class="fw-bolder rounded-0 balance negative">
                                                {{ 'Rs.' . number_format($account->prev_balance) }}
                                            </span>
                                        @endif
                                    </td>


                                    <style>
                                        .balance.positive {
                                            color: rgb(253, 27, 27);
                                        }

                                        .balance.negative {
                                            color: rgb(1, 149, 1);
                                        }

                                        .balance.neutral {
                                            color: #6c757d;
                                        }
                                    </style>


                                    <td>
                                        <div class="dropdown ms-auto">
                                            <button class="btn btn-dark btn-sm dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="dropdownMenuButton">

                                                @if ($account->id != 1)
                                                    <li>
                                                        <a class="dropdown-item" target="_BLANK"
                                                            href="{{ route('account.details', ['id' => $account->id]) }}">
                                                            View Details
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('account.ledger', ['id' => $account->id]) }}">
                                                            View Ledger
                                                        </a>
                                                    </li>
                                                @endif


                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('account.purchase.history', ['id' => $account->id]) }}">View
                                                        Purchace History</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ url('admin/sale-history?acc_id=' . $account->id) }}">View
                                                        Sale History</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ url('admin/return-history?acc_id=' . $account->id) }}">View
                                                        Return History</a>
                                                </li>

                                                <li>
                                                    <a class="dropdown-item" target="_BLANK"
                                                        href="{{ url('admin/invoices?account_id=' . $account->id . '&invoice_id=&date=') }}">View
                                                        Sale
                                                        Invoices</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" target="_BLANK"
                                                        href="{{ url('admin/invoices/return?account_id=' . $account->id . '&invoice_id=&date=') }}">View
                                                        Return
                                                        Invoices</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('account.edit', ['id' => $account->id]) }}">Edit</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="float-end mt-2">
                        {{ $accounts->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-size:14px;width:10%" class="text-dark fw-bold text-center">Acc ID</th>
                                <th style="font-size:14px;width:26%" class="text-dark fw-bold">Customer Name
                                </th>
                                <th style="font-size:14px;width:17%" class="text-dark fw-bold text-center">Phone
                                </th>
                                <th style="font-size:14px;width:25%" class="text-dark fw-bold">Address</th>
                                <th style="font-size:14px;width:20%" class="text-dark fw-bold text-center">Balance
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




    <script>
        $(":input").inputmask();
    </script>

@endsection
