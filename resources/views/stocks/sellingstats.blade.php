@extends('dashboard.master2')
@php
    $title = $title2;
@endphp
@section('admin_title', 'Stock Sale Stats | ' . $title)
@section('content2')
    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-6 col-sm-4">
                    <a href="{{ url('/admin') }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-home-circle me-2"></i> Dashboard
                    </a>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-8 col-9">
                    <h5 class="mt-1 d-none d-lg-none d-md-block" style="font-family:cursive">
                        Stock Sale Stats | {{ $title }}
                    </h5>
                    <h3 class="mt-1 d-none d-lg-block d-md-none" style="font-family:cursive">
                        Stock Sale Stats | {{ $title }}
                    </h3>
                    <small class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                        Stock Sale Stats | {{ $title }}
                    </small>
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

                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-1 mb-1">
                        <select name="limit" class="form-select" onchange="this.form.submit()">
                            <option value="10" {{ request()->input('limit', 10) == 10 ? 'selected' : '' }}>Limit 10
                            </option>
                            <option value="20" {{ request()->input('limit', 10) == 20 ? 'selected' : '' }}>20</option>
                            <option value="30" {{ request()->input('limit', 10) == 30 ? 'selected' : '' }}>30</option>
                            <option value="40" {{ request()->input('limit', 10) == 40 ? 'selected' : '' }}>40</option>
                            <option value="50" {{ request()->input('limit', 10) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request()->input('limit', 10) == 100 ? 'selected' : '' }}>100</option>

                            <!-- FIXED -->
                            <option value="all" {{ request()->input('limit') == 'all' ? 'selected' : '' }}>
                                No Limit
                            </option>
                        </select>
                    </div>






                </div>
            </form>
        </div>




        @if ($topSelling->count() < 1 && request()->limit)
            <div class="alert bg-warning text-white mt-3">
                <strong>No Results Found !</strong>
            </div>
        @endif






        <div class="bg-primary p-2" style="border-radius: 50px">
            <h3 class="mb-0 text-center text-white fw-bold">{{ $title2 }}</h5>
        </div>


        <div class="card p-2 mb-0 mt-2">
            @if ($topSelling->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Stock Name</th>
                                <th style="font-size:14px" class="text-dark fw-bold text-center">Total Sold</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topSelling as $key => $item)
                                <tr>
                                    <td class="text-dark">{{ ++$key }}</td>
                                    <td class="text-dark fw-bold">
                                        {{ $item->name }}
                                    </td>

                                    <td class="text-center"><span class="badge bg-primary">{{ $item->total_sold }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end fw-bolder text-dark" style="font-size: 20px;">Total</th>
                                <th class="text-center">
                                    <span class="badge p-2" style="background-color: rgb(1, 149, 1);font-size:10px">
                                        {{ $topSelling->sum('total_sold') }}
                                    </span>
                                </th>
                            </tr>
                        </tfoot>


                    </table>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Stock Name</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Total Sold</th>
                            </tr>

                        </thead>
                    </table>
                </div>

                <h4 class="text-center fw-normal text-dark mt-2">No Data Found!</h4>
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
        $('#type-select').select2({
            placeholder: 'Select Type',
            allowClear: true
        });

        $('#company-select').select2({
            placeholder: 'Select Company',
            allowClear: true
        });
    </script>
@endsection
