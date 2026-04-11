@extends('dashboard.master2')
@section('admin_title', 'Admin | Spendings')
@section('content2')

    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-7 col-md-4 col-sm-5">
                    <a href="{{ route('create.spendings') }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-plus me-1"></i> Create New Spending
                    </a>
                </div>
                <div class="col-lg-9 col-5 col-md-8 col-sm-7 ">

                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-lg-block d-md-block" style="font-family: cursive;">Spendings

                        </h3>
                        <h5 class="mt-1 d-block d-lg-none d-md-none" style="font-family: cursive;">Spendings</h5>
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
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mt-1 mb-1">
                        <input type="date" min="1" class="form-control" value="{{ request()->date }}"
                            name="date">
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mt-1 mb-1">
                        <select name="day" class="form-select" onchange="this.form.submit()">
                            <option value="">Select Day</option>
                            @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mt-1 mb-1">
                        <select name="month" class="form-select" onchange="this.form.submit()">
                            <option value="">Select Month</option>
                            @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ url('admin/spendings') }}" title="Clear" class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>





        <div class="card p-3 mt-2">

            @foreach ($grouped as $date => $items)
                <div class="card mb-3">
                    <div class="card-header bg-dark text-white">
                        <strong>
                            {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                            | {{ $items->first()->day }} |
                            Total Spend: {{ 'Rs.' . number_format($items->sum('amount')) }}
                        </strong>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-adrk fw-bolder">#</th>
                                    <th class="text-dark fw-bolder">Title</th>
                                    <th class="text-dark fw-bolder">Amount</th>
                                    <th class="text-dark fw-bolder">Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($items as $key => $spending)
                                    <tr>
                                        <td class="text-dark text-center">{{ ++$key }}</td>
                                        <td class="text-dark fw-bold">{{ $spending->title }}</td>
                                        <td class="text-danger fw-bold">{{ 'Rs.' . number_format($spending->amount) }}</td>
                                        <td class="text-dark">{{ $spending->description ?? 'N/A' }}</td>
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
                                                        href="{{ route('spendings.edit', ['id' => $spending->id]) }}">Edit Spend</a>
                                                </li>
                                                 <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('spendings.delete', ['id' => $spending->id]) }}">Delete Spend</a>
                                                </li>
                                            </ul>
                                        </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

        </div>

    </div>





@endsection
