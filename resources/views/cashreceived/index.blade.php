@extends('dashboard.master2')
@section('admin_title', 'Admin | Received Amounts')
@section('content2')

    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-6 col-md-4 col-sm-5">
                    <a href="{{ route('create.cash') }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-plus me-1"></i> Add New Receiving
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-8 col-sm-7">

                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-lg-block d-md-block" style="font-family: cursive;">Received Amounts</h3>
                        <h5 class="mt-1 d-block d-lg-none d-md-none" style="font-family: cursive;">Received Amounts</h5>

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
                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                        <select name="account_id" class="form-select" id="acc-select" onchange="this.form.submit()">
                            <option value="{{ null }}">Select Account</option>
                            @foreach ($accounts as $account)
                                @if ($account->id != 1)
                                    <option value="{{ $account->id }}"
                                        {{ request()->account_id == $account->id ? 'selected' : '' }}>
                                        {{ $account->id . ')' . $account->customer_name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>

                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                        <input type="month" class="form-control" value="{{ request()->month }}" name="month">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                        <input type="date" class="form-control" value="{{ request()->date }}" name="date">
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ url('admin/cash-received') }}" title="Clear"
                                class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        @if ($cash->count() > 0 && (request()->account_id || request()->date || request()->month))
            <div class="alert bg-primary text-white mt-3">
                <strong>{{ $cash->count() }} {{ $cash->count() > 0 && $cash->count() < 2 ? 'Result' : 'Results' }}
                    Found</strong>
            </div>
        @elseif ($cash->count() < 1 && (request()->account_id || request()->date || request()->month))
            <div class="alert bg-warning text-white mt-3">
                <strong>No Results Found !</strong>
            </div>
        @endif


        <div class="card p-2 mb-0">
            @if ($cash->count() > 0)
                <form action="{{ route('cash.received.download.pdf') }}" method="post">
                    @csrf
                    <input type="hidden" name="account_id" value="All">
                    <input type="submit" value="Download PDF" class="btn btn-primary float-end mt-2">
                </form>


                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-size:14px" class="text-dark fw-bold text-center">#</th>
                                <th style="font-size:14px;width:9%" class="text-dark fw-bold text-center">Acc ID</th>
                                <th style="font-size:14px;width:28%" class="text-dark fw-bold">Customer Name</th>
                                <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Narration</th>
                                <th style="font-size:14px;width:20%" class="text-dark fw-bold text-center">Amount Received
                                </th>
                                <th style="font-size:14px" class="text-dark fw-bold text-center">R.A.R</th>
                                <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Date</th>
                                <th style="font-size:14px" class="text-dark fw-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cash as $key => $csh)
                                <tr>
                                    <td class="text-dark text-center">{{ ++$key }}</td>
                                    <td class="text-dark text-center">{{ $csh->account_id }}</td>
                                    <td>
                                        <a class="text-dark fw-bold" target="_BLANK"
                                            href="{{ route('cash.view.receipt', ['id' => $csh->id]) }}">
                                            {{ $csh->customer_name }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a class="text-dark" target="_BLANK"
                                            href="{{ route('cash.edit', ['id' => $csh->id]) }}">
                                            {{ $csh->narration }}
                                        </a>
                                    </td>
                                    <td class="text-dark text-center fw-bold" style="font-size: 18px;">
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
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('cash.view.receipt', ['id' => $csh->id]) }}">View
                                                        Receipt</a>
                                                </li>
                                                @php
                                                    $canEditOrDelete = now()->diffInHours($csh->created_at) < 24;
                                                @endphp

                                                @if ($canEditOrDelete)
                                                    <li>
                                                        <a class="dropdown-item" title="Edit"
                                                            href="{{ route('cash.edit', ['id' => $csh->id]) }}">Edit</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" title="Delete"
                                                            onclick="confirmDelete('{{ route('cash.delete', ['id' => $csh->id]) }}')">Delete</a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a class="dropdown-item text-muted disabled" href="#">Edit
                                                            (Disabled 24h Passed)</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-muted disabled" href="#">Delete
                                                            (Disabled 24h Passed)</a>
                                                    </li>
                                                @endif

                                            </ul>
                                        </div>
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="float-end mt-2">
                        {{ $cash->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px;width:9%" class="text-dark fw-bold">Acc ID</th>
                                <th style="font-size:14px;width:26%" class="text-dark fw-bold">Customer Name</th>
                                <th style="font-size:14px;width:13%" class="text-dark fw-bold">Narration</th>
                                <th style="font-size:14px;width:20%" class="text-dark fw-bold">Amount Received</th>
                                <th style="font-size:14px" class="text-dark fw-bold">R.A.R</th>
                                <th style="font-size:14px;width:15%" class="text-dark fw-bold">Date</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <h4 class="h4 text-center fw-normal text-muted mt-2">No Data Found!</h4>
            @endif
        </div>

    </div>


    <script>
        // Function to handle delete confirmation
        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure you want to delete this Entry?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }
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
