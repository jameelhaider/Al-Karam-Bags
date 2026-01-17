@extends('dashboard.master2')
@php
    $title =
        request()->type == 'handcarries'
            ? 'Admin | Demands | Hand Carries'
            : (request()->type == 'handbags'
                ? 'Admin | Demands | Hand Bags'
                : (request()->type == 'schoolbags'
                    ? 'Admin | Demands | School Bags'
                    : (request()->type == 'travelbags'
                        ? 'Admin | Demands | Travel Bags'
                        : 'Title Not Found')));
@endphp
@section('admin_title', $title)
@section('content2')

    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-6 col-md-4 col-sm-5">
                    <a href="{{ route('create.demand', ['type' => request()->type]) }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-plus me-1"></i> Add New Demand
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-8 col-sm-7">

                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-lg-block d-md-block" style="font-family: cursive;">
                            {{ request()->type == 'handcarries'
                                ? 'Demands | Hand Carries'
                                : (request()->type == 'handbags'
                                    ? 'Demands | Hand Bags'
                                    : (request()->type == 'schoolbags'
                                        ? 'Demands | School Bags'
                                        : (request()->type == 'travelbags'
                                            ? 'Demands | Travel Bags'
                                            : 'Title Not Found'))) }}
                        </h3>
                        <h5 class="mt-1 d-block d-lg-none d-md-none" style="font-family: cursive;">
                            {{ request()->type == 'handcarries'
                                ? 'Demands | Hand Carries'
                                : (request()->type == 'handbags'
                                    ? 'Demands | Hand Bags'
                                    : (request()->type == 'schoolbags'
                                        ? 'Demands | School Bags'
                                        : (request()->type == 'travelbags'
                                            ? 'Demands | Travel Bags'
                                            : 'Title Not Found'))) }}
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
                    <div class="col-lg-9 col-md-8 col-sm-8 col-12 mt-1 mb-1">
                        <input type="text" class="form-control" value="{{ request()->name }}" placeholder="Demand Name"
                            name="name">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4 col-12 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ url('admin/demands/' . request()->type) }}" title="Clear"
                                class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        @if ($demands->count() > 0 && request()->name)
            <div class="alert bg-primary text-white mt-3">
                <strong>{{ $demands->count() }}
                    {{ $demands->count() > 0 && $demands->count() < 2 ? 'Result' : 'Results' }}
                    Found</strong>
            </div>
        @elseif ($demands->count() < 1 && request()->name != null)
            <div class="alert bg-warning text-white mt-3">
                <strong>No Results Found !</strong>
            </div>
        @endif


        <div class="card p-2 mb-0 mt-2">
            <h3 class="d-none fw-bold text-center mt-2">Demands</h3>
            <hr class="d-none">
            @if ($demands->count() > 0)

                <form id="delete-selected-form" method="POST" action="{{ route('demand.delete.selected') }}">
                    @csrf
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <button type="submit" class="btn btn-warning" onclick="confirmDelete3(event)">
                                Delete Selected
                            </button>

                            <a class="btn btn-danger"
                                onclick="confirmDelete2('{{ route('demand.delete.all', ['type' => request()->type]) }}')"
                                href="#">
                                Delete All
                            </a>

                        </div>

                    </div>



                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr class="text-center">
                                    <th><input type="checkbox" class="form-check-input border border-primary"
                                            style="transform: scale(1.5);" id="select-all"></th>
                                    <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                    <th style="font-size:14px" class="text-dark fw-bold">Demand Name</th>
                                    <th style="font-size:14px" class="text-dark fw-bold">Qty</th>
                                    <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($demands as $key => $demand)
                                    <tr class="text-center">
                                        <td>
                                            <input type="checkbox" class="form-check-input border border-primary"
                                                style="transform: scale(1.1);" name="selected_ids[]"
                                                value="{{ $demand->id }}">
                                        </td>


                                        <td class="text-dark">{{ ++$key }}</td>
                                        <td title="{{ $demand->name }}">
                                            <a class="text-dark fw-bold"
                                                href="{{ route('demand.edit', ['type' => $demand->type, 'id' => $demand->id]) }}">
                                                {{ $demand->name }}
                                            </a>
                                        </td>

                                        <td class="text-dark" title="{{ $demand->qty }}">{{ $demand->qty ?: '------' }}
                                        </td>
                                        <td>
                                            <a style="color: red" title="Delete" href="javascript:void(0)"
                                                onclick="confirmDelete('{{ route('demand.delete', ['id' => $demand->id]) }}')">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="float-end mt-2">
                            {{ $demands->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </form>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Demand Name</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Qty</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <h4 class="h4 text-center fw-normal text-dark mt-2">No Data Found!</h4>
            @endif

        </div>



    </div>






    <script>
        document.getElementById('select-all').onclick = function() {
            var checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }
    </script>

    <script>
        // Function to handle delete confirmation
        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure you want to delete this Demand?',
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


        function confirmDelete2(url) {
            Swal.fire({
                title: 'Are you sure you want to delete All Demands?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete All!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }

        function confirmDelete3(e) {
            e.preventDefault(); // Prevent immediate form submission
            Swal.fire({
                title: 'Are you sure you want to delete All Selected Demands?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete All Selected!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-selected-form').submit();
                }
            });
        }
    </script>

@endsection
