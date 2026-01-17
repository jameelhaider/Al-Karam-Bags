@extends('dashboard.master2')
@section('admin_title', 'Admin | Return History')
@section('content2')
    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-6 col-sm-4">
                    <a href="{{ url('/admin') }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-home-circle me-1"></i> Dashboard
                    </a>
                </div>
                <div class="col-lg-9 col-md-9 col-6 col-sm-8">

                    <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">
                        Admin | Return History
                    </h3>

                    <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                        Admin | Return History
                    </h5>

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
                    <div class="col-lg-3 col-md-4 col-sm-4 col-4 mt-1 mb-1">
                        <select name="acc_id" class="form-select" id="acc-select" onchange="this.form.submit()">
                            <option value="{{ null }}">Select Account</option>
                            @foreach ($accounts as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ request()->acc_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->id . ') ' . $customer->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>

                        <div class="col-lg-2 col-md-4 col-sm-4 col-4 mt-1 mb-1">
                        <input type="text" placeholder="Name" class="form-control" value="{{ request()->name }}" name="name">
                    </div>

                    <div class="col-lg-2 col-md-4 col-sm-4 col-4 mt-1 mb-1">
                        <input type="month" class="form-control" value="{{ request()->month }}" name="month">
                    </div>

                    <div class="col-lg-2 col-md-6 col-sm-6 col-6 mt-1 mb-1">
                        <input type="date" class="form-control" value="{{ request()->date }}" name="date">
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ url('admin/return-history') }}" title="Clear"
                                class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        @if ($items->count() > 0 && (request()->date || request()->acc_id || request()->month || request()->name))
            <div class="alert bg-primary text-white mt-3">
                <strong>{{ $items->count() }}
                    {{ $items->count() > 0 && $items->count() < 2 ? 'Result' : 'Results' }}
                    Found</strong>
            </div>
        @elseif ($items->count() < 1 && (request()->date || request()->acc_id || request()->month || request()->name))
            <div class="alert bg-warning text-white mt-3">
                <strong>No Results Found !</strong>
            </div>
        @endif





        <div class="card p-2 mb-0 mt-2">
            @if ($items->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">#</th>
                                <th style="font-size:14px;width:25%" class="text-dark fw-bold">Name
                                </th>
                                <th style="font-size:14px;width:10%" class="text-dark text-center fw-bold">Price</th>
                                <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">Qty
                                </th>
                                <th style="font-size:14px;width:10%" class="text-dark fw-bold text-center">Total
                                </th>
                                <th style="font-size:14px;width:25%" class="text-dark fw-bold">ID | View | From</th>

                                <th style="font-size:14px;width:12%" class="text-dark fw-bold text-center">Return Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $key => $item)
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
                                               target="_BLANK" class="btn btn-xs btn-primary">{{ $item->invoice_id }} | View</a>
                                            <br>
                                        @endif
                                        {{ $item->invoice_to }}
                                    </td>

                                    <td class="text-dark text-center">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d M y') }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="float-end mt-2">
                        {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">#</th>
                                <th style="font-size:14px;width:30%" class="text-dark fw-bold">Name
                                </th>
                                <th style="font-size:14px;width:10%" class="text-dark text-center fw-bold">Price</th>
                                <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">Qty
                                </th>
                                <th style="font-size:14px;width:10%" class="text-dark fw-bold text-center">Total
                                </th>
                                <th style="font-size:14px;width:15%" class="text-dark fw-bold">ID | View | From</th>

                                <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Return Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <h4 class="h4 text-center fw-normal text-muted mt-2">No Data Found!</h4>
            @endif
        </div>

    </div>







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
