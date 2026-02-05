@extends('dashboard.master2')
@php
    $title =
        request()->type == 'handcarries'
            ? 'Admin | Stocks | Hand Carries'
            : (request()->type == 'handbags'
                ? 'Admin | Stocks | Hand Bags'
                : (request()->type == 'schoolbags'
                    ? 'Admin | Stocks | School Bags'
                    : (request()->type == 'travelbags'
                        ? 'Admin | Stocks | Travel Bags'
                        : 'Title Not Found')));
@endphp

@section('admin_title', $title)
@section('content2')

    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-6 col-md-4 col-sm-5">
                    <a href="{{ route('create.stock', ['type' => request()->type]) }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-plus me-1"></i>
                        Add New Stock
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-8 col-sm-7">

                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family: cursive;">
                            {{ request()->type == 'handcarries'
                                ? 'Hand Carries'
                                : (request()->type == 'handbags'
                                    ? 'Hand Bags'
                                    : (request()->type == 'schoolbags'
                                        ? 'School Bags'
                                        : (request()->type == 'travelbags'
                                            ? 'Travel Bags'
                                            : 'Title Not Found'))) }}
                        </h3>



                        <h5 class="mt-1 d-block d-lg-none d-md-none d-sm-block" style="font-family: cursive;">
                            {{ request()->type == 'handcarries'
                                ? 'Hand Carries'
                                : (request()->type == 'handbags'
                                    ? 'Hand Bags'
                                    : (request()->type == 'schoolbags'
                                        ? 'School Bags'
                                        : (request()->type == 'travelbags'
                                            ? 'Travel Bags'
                                            : 'Title Not Found'))) }}
                        </h5>





                        <!-- Dropdown Button -->
                        <div class="dropdown d-inline-block">
                            <button type="button" style="border-radius: 0%"
                                class="btn btn-dark btn-sm ms-3 dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bx bx-show"></i> Show
                            </button>

                            <!-- Dropdown Menu -->
                            <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 200px;">
                                <p class="text-muted" style="font-size: 13px;">COLUMN</p>

                                <form action="{{ route('update.show') }}" method="POST" id="showForm">
                                    @csrf

                                    <!-- Quantity checkbox -->
                                    <div class="form-check">
                                        <input type="hidden" name="is_show_qty" value="0">
                                        <input class="form-check-input" type="checkbox" value="1" id="checkbox1"
                                            name="is_show_qty" {{ isshowqty() == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="checkbox1">Quantity</label>
                                    </div>

                                    <!-- Purchase checkbox -->
                                    <div class="form-check">
                                        <input type="hidden" name="is_show_purchase" value="0">
                                        <input class="form-check-input" type="checkbox" value="1" id="checkbox2"
                                            name="is_show_purchase" {{ isshowpurchase() == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="checkbox2">Purchase</label>
                                    </div>

                                    <!-- Sale checkbox -->
                                    <div class="form-check">
                                        <input type="hidden" name="is_show_sale" value="0">
                                        <input class="form-check-input" type="checkbox" value="1" id="checkbox3"
                                            name="is_show_sale" {{ isshowsale() == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="checkbox3">Sale</label>
                                    </div>



                                    <!-- Status checkbox -->
                                    <div class="form-check">
                                        <input type="hidden" name="is_show_status" value="0">
                                        <input class="form-check-input" type="checkbox" value="1" id="checkbox4"
                                            name="is_show_status" {{ isshowstatus() == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="checkbox4">Status</label>
                                    </div>
                                    <!-- Status checkbox -->
                                    <div class="form-check">
                                        <input type="hidden" name="is_show_action" value="0">
                                        <input class="form-check-input" type="checkbox" value="1" id="checkbox5"
                                            name="is_show_action" {{ isshowaction() == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="checkbox5">Action</label>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <script>
                            // jQuery to handle form submission on checkbox change
                            $(document).ready(function() {
                                $('.form-check-input').on('change', function() {
                                    $('#showForm').submit(); // Submit the form on change event
                                });
                            });
                        </script>




                        <div class="ms-4 d-none d-lg-block d-md-block">
                            <form action="" method="get">
                                {{-- Preserve search Name --}}
                                @if (request()->name)
                                    <input type="hidden" name="name" value="{{ request()->name }}">
                                @endif

                                {{-- Status Dropdown --}}
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">All</option>
                                    <option value="AVAILABLE" {{ request()->status == 'AVAILABLE' ? 'selected' : '' }}>
                                        AVAILABLE</option>
                                    <option value="OUT OF STOCK"
                                        {{ request()->status == 'OUT OF STOCK' ? 'selected' : '' }}>
                                        OUT OF STOCK
                                    </option>
                                </select>
                            </form>
                        </div>


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
                <input type="hidden" name="status" value="{{ request()->status }}">
                <div class="row">


                    {{-- Name --}}
                    <div class="col-lg-9 col-md-3 col-sm-6 col-12 mt-1 mb-1">
                        <input type="text" class="form-control" value="{{ request()->name }}" placeholder="Name"
                            name="name">
                    </div>

                    {{-- Buttons --}}
                    <div class="col-lg-3 col-md-3 col-sm-6 col-12 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ route('stock.index', ['type' => request()->type]) }}" title="Clear"
                                class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>
                        </div>
                    </div>


                </div>
            </form>
        </div>



        @if ($stocks->count() > 0 && request()->name)
            <div class="alert bg-primary text-white mt-3">
                <strong>{{ $stocks->count() }} {{ $stocks->count() > 0 && $stocks->count() < 2 ? 'Result' : 'Results' }}
                    Found</strong>
            </div>
        @elseif ($stocks->count() < 1 && request()->name)
            <div class="alert bg-warning text-white mt-3">
                <strong>No Results Found !</strong>
            </div>
        @endif





        <div class="card p-2 mb-0">
            @if ($stocks->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Name</th>
                                @if (isshowqty() == '1')
                                    <th style="font-size:14px" class="text-dark fw-bold text-center">Quantity</th>
                                @endif
                                @if (isshowpurchase() == '1')
                                    <th style="font-size:14px" class="text-dark fw-bold">Purchase
                                    </th>
                                @endif
                                @if (isshowsale() == '1')
                                    <th style="font-size:14px" class="text-dark fw-bold">Sale</th>
                                @endif
                                @if (isshowstatus() == '1')
                                    <th style="font-size:14px" class="text-dark fw-bold">Status</th>
                                @endif
                                @if (isshowaction() == '1')
                                    <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                                @endif




                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stocks as $key => $stock)
                                <tr>
                                    <td class="text-dark">{{ ++$key }}</td>

                                    <td style="width:37%;font-size: 16px" title="{{ $stock->name }}"><a
                                            href="{{ route('stock.edit', ['id' => $stock->id, 'type' => request()->type]) }}"
                                            class="text-dark fw-bold">
                                            {{ $stock->name }}
                                        </a>
                                    </td>
                                    @if (isshowqty() == '1')
                                        <td class="text-center text-dark" title="{{ number_format($stock->qty) }}">
                                            {{ number_format($stock->qty) }}</td>
                                    @endif

                                    @if (isshowpurchase() == '1')
                                        <td class="text-dark fw-light" style="font-size: 15px"
                                            title="Rs.{{ $stock->purchase_price }}">
                                            Rs.{{ number_format($stock->purchase_price) }}</td>
                                    @endif

                                    @if (isshowsale() == '1')
                                        <td class="fw-bold text-dark" style="font-size: 20px"
                                            title="Rs.{{ number_format($stock->sale_price) }}">
                                            Rs.{{ number_format($stock->sale_price) }}</td>
                                    @endif



                                    @if (isshowstatus() == '1')
                                        <td>
                                            @if ($stock->qty <= 0)
                                                <span class="badge" style="background-color: rgb(253, 27, 27)"
                                                    title="Out Of Stock">
                                                    Out Of Stock
                                                </span>
                                            @else
                                                <span class="badge" style="background-color: rgb(1, 149, 1)"
                                                    title="Available">
                                                    Available
                                                </span>
                                                @if ($stock->qty <= $stock->alert_qty)
                                                    <span class="badge" style="background-color: rgb(255, 162, 0);"
                                                        title="Low Stock">
                                                        Low Stock
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                    @endif



                                    @if (isshowaction() == '1')
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
                                                        <a class="dropdown-item" href="#"
                                                            onclick="openDemandModal({{ $stock->id }})">Add To
                                                            Demand</a>
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('stock.edit', ['id' => $stock->id, 'type' => request()->type]) }}">Edit</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            onclick="confirmDelete('{{ route('stock.delete', ['id' => $stock->id]) }}')">Delete</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    @endif











                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="float-end mt-2">
                        {{ $stocks->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Name</th>
                                <th style="font-size:14px" class="text-dark fw-bold text-center">Quantity</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Sale</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Status</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                            </tr>

                        </thead>
                    </table>
                </div>

                <h4 class="text-center fw-normal text-dark mt-2">No Data Found!</h4>
            @endif
        </div>

    </div>











    <!-- Demand Modal -->
    <div class="modal fade" id="demandModal" tabindex="-1" aria-labelledby="demandModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('add.stock.demand') }}">
                @csrf
                <input type="hidden" name="stock_id" id="modalStockId">
                <input type="hidden" name="type" value="{{ request()->type }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="demandModalLabel">Add to Demand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="qty" class="form-label">Quantity (optional)</label>
                            <input type="number" placeholder="Quantity (optional)" class="form-control" name="qty"
                                id="qty" min="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <script>
        function openDemandModal(stockId) {
            document.getElementById('modalStockId').value = stockId;
            var myModal = new bootstrap.Modal(document.getElementById('demandModal'));
            myModal.show();
        }
    </script>







    <script>
        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure you want to delete this Stock?',
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

@endsection
