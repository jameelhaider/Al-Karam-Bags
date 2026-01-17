@extends('components.stockstabs')
@section('admin_title', 'Admin | Stocks')
@section('content3')

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
                            {{ request()->type == 'parts' ? 'Parts' : 'Tools' }} Stock Items</h3>

                        <h5 class="mt-1 d-block d-lg-none d-md-none d-sm-block" style="font-family: cursive;">
                            {{ request()->type == 'parts' ? 'Parts' : 'Tools' }} Stock Items
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

                                {{-- Preserve selected Companies --}}
                                @if (is_array(request()->company_id))
                                    @foreach (request()->company_id as $cid)
                                        <input type="hidden" name="company_id[]" value="{{ $cid }}">
                                    @endforeach
                                @elseif(request()->company_id)
                                    <input type="hidden" name="company_id[]" value="{{ request()->company_id }}">
                                @endif

                                {{-- Preserve selected Part Types --}}
                                @if (is_array(request()->part_type))
                                    @foreach (request()->part_type as $pid)
                                        <input type="hidden" name="part_type[]" value="{{ $pid }}">
                                    @endforeach
                                @elseif(request()->part_type)
                                    <input type="hidden" name="part_type[]" value="{{ request()->part_type }}">
                                @endif

                                {{-- Preserve search Name --}}
                                @if (request()->name)
                                    <input type="hidden" name="name" value="{{ request()->name }}">
                                @endif

                                {{-- Status Dropdown --}}
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">All</option>
                                    <option value="AVAILABLE" {{ request()->status == 'AVAILABLE' ? 'selected' : '' }}>
                                        AVAILABLE</option>
                                    <option value="AVAILABLE, LOW STOCK"
                                        {{ request()->status == 'AVAILABLE, LOW STOCK' ? 'selected' : '' }}>
                                        AVAILABLE, LOW STOCK
                                    </option>
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

                    {{-- Company Multi Select --}}
                    <div class="col-lg-3 col-md-3 col-sm-6 col-12 mt-1 mb-1">
                        <select name="company_id[]" id="company-select" class="form-select" multiple
                            onchange="this.form.submit()">
                            <option disabled>Select Company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ collect(request()->company_id)->contains($company->id) ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Name --}}
                    <div class="col-lg-3 col-md-3 col-sm-6 col-12 mt-1 mb-1">
                        <input type="text" class="form-control" value="{{ request()->name }}" placeholder="Name"
                            name="name">
                    </div>

                    {{-- Part Type Multi Select --}}
                    <div class="col-lg-3 col-md-3 col-sm-6 col-12 mt-1 mb-1">
                        <select name="part_type[]" id="type-select" class="form-select" multiple
                            onchange="this.form.submit()">
                            <option disabled>Select
                                @if (request()->type == 'tools')
                                    Tool
                                @else
                                    Part
                                @endif
                                Type
                            </option>
                            @foreach ($types as $parttype)
                                <option value="{{ $parttype->id }}"
                                    {{ collect(request()->part_type)->contains($parttype->id) ? 'selected' : '' }}>
                                    {{ $parttype->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-lg-3 col-md-3 col-sm-6 col-12 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a @if (request()->type == 'tools') href="{{ url('admin/stocks/tools') }}"
                           @elseif (request()->type == 'parts') href="{{ url('admin/stocks/parts') }}" @endif
                                title="Clear" class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>
                        </div>
                    </div>


                </div>
            </form>
        </div>



        @if ($stocks->count() > 0 && (request()->company_id || request()->name || request()->part_type))
            <div class="alert bg-primary text-white mt-3">
                <strong>{{ $stocks->count() }} {{ $stocks->count() > 0 && $stocks->count() < 2 ? 'Result' : 'Results' }}
                    Found</strong>
            </div>
        @elseif ($stocks->count() < 1 && (request()->company_id || request()->name || request()->part_type))
            <div class="alert bg-warning text-white mt-3">
                <strong>No Results Found !</strong>
            </div>
        @endif





        <div class="card p-2 mb-0">
            @if ($stocks->count() > 0)

                @if (request()->type == 'parts')
                    <form action="{{ route('stock.parts.download.pdf') }}" method="post">
                        @csrf
                        <input type="hidden" name="type_id" value="All">
                        <input type="hidden" name="company_id" value="All">
                        <input type="hidden" name="status" value="both">
                        <input type="hidden" name="show_price" value="1">
                        <input type="hidden" name="show_qty" value="1">
                        <input type="submit" class="btn btn-primary float-end" value="Download PDF">
                    </form>
                @else
                    <form action="{{ route('stock.tools.download.pdf') }}" method="post">
                        @csrf
                        <input type="hidden" name="company_id" value="All">
                        <input type="hidden" name="status" value="both">
                        <input type="hidden" name="show_price" value="1">
                        <input type="hidden" name="show_qty" value="1">
                        <input type="submit" class="btn btn-primary float-end" value="Download PDF">
                    </form>
                @endif



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

                                                    @if ($stock->qty > 0)
                                                        <li>
                                                            <a class="dropdown-item" href="#"
                                                                onclick="openInvoiceModal({{ $stock->id }}, '{{ addslashes($stock->name) }}', {{ $stock->qty }}, {{ $stock->purchase_price }}, {{ $stock->sale_price }})">
                                                                Add To Sale Invoice
                                                            </a>
                                                        </li>
                                                    @endif



                                                    {{-- @if (request()->type == 'parts') --}}
                                                    <li>
                                                        <a class="dropdown-item" href="#"
                                                            onclick="openDemandModal({{ $stock->id }})">Add To
                                                            Demand</a>
                                                    </li>
                                                    {{-- @endif --}}



                                                    {{-- <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('stock.view', ['id' => $stock->id]) }}">View</a>
                                                </li> --}}

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

                <h4 class="text-center fw-normal text-muted mt-2">No Data Found!</h4>
            @endif
        </div>

    </div>

























    <!-- Demand Modal -->
    <div class="modal fade" id="demandModal" tabindex="-1" aria-labelledby="demandModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('add.stock.demand') }}">
                @csrf
                <input type="hidden" name="stock_id" id="modalStockId">
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





    <!-- Invoice Modal -->
    <!-- Invoice Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('add.item.invoice') }}">
                @csrf
                <input type="hidden" name="stock_id" id="modalStockId2">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add to Sale Invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <strong>Item:</strong> <span id="modalItemName" class="text-primary"></span><br>
                            <strong>Available Qty:</strong> <span id="modalAvailableQty" class="text-success"></span>
                        </div>

                        <div class="mb-3">
                            <label for="invoice_no" class="form-label">Sale Invoice No <span
                                    class="text-danger">*</span></label>
                            <input type="number" required placeholder="Invoice No" class="form-control"
                                name="invoice_no" id="invoice_no" min="1">
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" required placeholder="Quantity" class="form-control" name="quantity"
                                id="quantity">
                        </div>
                        <div class="mb-3">
                            <label for="final_price" class="form-label">Final Price <span
                                    class="text-danger">*</span></label>
                            <input type="number" required placeholder="Final Price" class="form-control"
                                name="final_price" id="final_price">
                        </div>
                        <div class="mb-3">
                            <label for="total_price" class="form-label">Total Price</label>
                            <input type="number" class="form-control" id="total_price" readonly>
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
        function openInvoiceModal(stockId, itemName, availableQty, purchasePrice, salePrice) {
            // Set values in modal
            document.getElementById('modalStockId2').value = stockId;
            document.getElementById('modalItemName').textContent = itemName;
            document.getElementById('modalAvailableQty').textContent = availableQty;

            const quantityInput = document.getElementById('quantity');
            const finalPriceInput = document.getElementById('final_price');
            const totalPriceInput = document.getElementById('total_price');

            // Set initial values
            quantityInput.min = 1;
            quantityInput.max = availableQty;
            quantityInput.value = 1;

            finalPriceInput.min = parseFloat(purchasePrice);
            finalPriceInput.value = parseFloat(salePrice);

            // Function to calculate total
            const calculateTotal = () => {
                const qty = parseFloat(quantityInput.value) || 0;
                const price = parseFloat(finalPriceInput.value) || 0;
                totalPriceInput.value = (qty * price).toFixed(2);
            };

            // Initial total
            calculateTotal();

            // Add event listeners
            quantityInput.addEventListener('input', calculateTotal);
            finalPriceInput.addEventListener('input', calculateTotal);

            // Show modal
            const myModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
            myModal.show();
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
        $('#company-select').select2({
            placeholder: 'Select Company',
            allowClear: true
        });

        $('#type-select').select2({
            placeholder: 'Select Type',
            allowClear: true
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(":input").inputmask();
    </script>



    <script>
        $(document).ready(function() {
            // Initialize counter-up
            $('.count-animation').each(function() {
                var $this = $(this),
                    countTo = $this.attr('data-count');

                $this.prop('Counter', 0).animate({
                    Counter: countTo
                }, {
                    duration: 2000, // Duration of the animation in milliseconds
                    easing: 'swing', // Easing function
                    step: function(now) {
                        $this.text('RS.' + Math.ceil(now).toLocaleString());
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const balanceToggle = document.getElementById('balance-toggle');
            const balance = document.getElementById('balance');
            const toggleText = document.getElementById('toggle-text');
            const toggleIcon = document.getElementById('toggle-icon');

            // Check localStorage for the balance visibility state
            const balanceVisible = localStorage.getItem('balanceVisible') === 'true';

            // Set the initial state based on localStorage
            if (balanceVisible) {
                balance.style.display = 'block';
                toggleText.textContent = 'Hide Balance';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                balance.style.display = 'none';
                toggleText.textContent = 'Show Balance';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }

            // Toggle balance visibility and update localStorage
            balanceToggle.addEventListener('click', function() {
                if (balance.style.display === 'none') {
                    balance.style.display = 'block';
                    toggleText.textContent = 'Hide Balance';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                    localStorage.setItem('balanceVisible', 'true');
                } else {
                    balance.style.display = 'none';
                    toggleText.textContent = 'Show Balance';
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                    localStorage.setItem('balanceVisible', 'false');
                }
            });
        });
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
