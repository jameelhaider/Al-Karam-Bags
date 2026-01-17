@extends('dashboard.master2')
@section('admin_title', 'Admin | Sale History')
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
                    @php
                        use Carbon\Carbon;

                        $formattedDate = null;

                        if (request()->date) {
                            $formattedDate = Carbon::parse(request()->date)->format('d F Y'); // 12 August 2025
                        } elseif (request()->month) {
                            $formattedDate = Carbon::createFromFormat('Y-m', request()->month)->format('F Y'); // June 2025
                        }
                    @endphp

                    <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">
                        Admin | Sale History
                        @if ($formattedDate)
                            | {{ $formattedDate }}
                        @endif
                    </h3>

                    <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                        Admin | Sale History
                        @if ($formattedDate)
                            | {{ $formattedDate }}
                        @endif
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
                            <option value="">Select Account</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ request()->acc_id == $account->id ? 'selected' : '' }}>
                                    {{ $account->id . ') ' . $account->customer_name }}</option>
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
                            <a href="{{ url('admin/sale-history') }}" title="Clear"
                                class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        @if ($items->count() > 0 && (request()->date || request()->month || request()->acc_id || request()->name))
            <div class="alert bg-primary text-white mt-3">
                <strong>{{ $items->count() }}
                    {{ $items->count() > 0 && $items->count() < 2 ? 'Result' : 'Results' }}
                    Found</strong>
            </div>
        @elseif ($items->count() < 1 && (request()->date || request()->month || request()->acc_id || request()->name))
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
                                <th style="font-size:14px;width:30%" class="text-dark fw-bold">Name
                                </th>
                                <th style="font-size:14px;width:8%" class="text-dark text-center fw-bold">Price</th>
                                <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">Qty
                                </th>
                                <th style="font-size:14px;width:8%" class="text-dark fw-bold text-center">Total
                                </th>
                                <th style="font-size:14px;width:30%" class="text-dark fw-bold">To | No | Return</th>

                                <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $key => $item)
                                <tr>
                                    <td class="text-dark text-center">{{ ++$key }}</td>

                                    <td class="text-dark fw-bold" style="font-size: 13px">
                                        {{ $item->item_name }}
                                    </td>

                                    <td class="text-dark text-center">
                                        {{ 'Rs.' . number_format($item->item_price) }}
                                    </td>
                                    <td class="text-dark text-center">
                                        {{ number_format($item->item_qty) }}
                                    </td>

                                    <td class="text-dark text-center">
                                        {{ 'Rs.' . number_format($item->item_total) }}
                                    </td>
                                    <td class="text-dark fw-bold">
                                        {{ $item->invoice_to }}
                                        <br>
                                        <a href="{{ route('invoice.view', ['id' => $item->invoice_no]) }}" target="_Blank"
                                            class="btn btn-xs btn-primary mt-1"> {{ $item->invoice_no }} | View</a>
                                        @if ($item->item_stock_id != null && $item->status != 'Returned')
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#returnModal"
                                                data-invoice-id="{{ $item->invoice_no }}"
                                                data-item-id="{{ $item->item_id }}"
                                                data-item-name="{{ $item->item_name }}"
                                                data-item-qty="{{ $item->item_qty }}"
                                                data-item-status="{{ $item->status }}"
                                                data-item-partial-qty="{{ $item->partial_qty ?? 0 }}"
                                                data-item-price="{{ $item->item_price }}"
                                                class="btn btn-danger btn-xs mt-1">Return <i class="bx bx-undo"></i></a>
                                        @else
                                            <span class="fw-light" style="color: rgb(253, 27, 27)">Returned</span>
                                        @endif
                                    </td>

                                    <td class="text-dark text-center">
                                        {{ \Carbon\Carbon::parse($item->sold_date)->format('d M y') }}
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
                                <th style="font-size:14px;width:8%" class="text-dark text-center fw-bold">Price</th>
                                <th style="font-size:14px;width:5%" class="text-dark fw-bold text-center">Qty
                                </th>
                                <th style="font-size:14px;width:8%" class="text-dark fw-bold text-center">Total
                                </th>
                                <th style="font-size:14px;width:30%" class="text-dark fw-bold">To | No | Return</th>

                                <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <h4 class="h4 text-center fw-normal text-muted mt-2">No Data Found!</h4>
            @endif
        </div>






    </div>




    <!-- Return Modal -->
    <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('return.invoice.item') }}" method="POST">
                @csrf
                <input type="hidden" name="invoice_id" id="modalInvoiceId">
                <input type="hidden" name="item_id" id="modalItemId">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="returnModalLabel">Return Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p><strong>Item Name:</strong> <span id="modalItemName" class="text-danger fw-bold"></span>
                        </p>
                        <p><strong>Actual Qty:</strong> <span id="modalActualQty" class="fw-bold text-danger"></span>
                        </p>
                        <p><strong>Already Returned:</strong> <span id="modalReturnedQty"
                                class="fw-bold text-danger"></span></p>
                        <p><strong>Returnable Qty:</strong> <span id="modalReturnableQty"
                                class="text-danger fw-bold"></span></p>
                        <p><strong>Status:</strong> <span id="modalStatus" class="text-danger fw-bold"></span></p>

                        <!-- Action Dropdown -->
                        <div id="actionWrapper">
                            <label for="actionSelect" class="fw-bold">Select Action <span
                                    class="text-danger">*</span></label>
                            <select name="action" class="form-select" id="actionSelect">
                                <option value="">Select Option</option>
                                <option value="Return Complete Item">Return Complete Item</option>
                                <option value="Return Some Qty">Return Some Qty</option>
                            </select>
                        </div>

                        <!-- Quantity Input -->
                        <div id="qtyWrapper" class="mt-2" style="display: none;">
                            <label for="returnQty" class="fw-bold">Return Qty <span class="text-danger">*</span></label>
                            <input type="number" min="1" name="return_qty" class="form-control" id="returnQty"
                                placeholder="Qty To Return">
                        </div>

                        <div class="mt-2">
                            <label for="returnPrice" class="fw-bold">Return Price <span
                                    class="text-danger">*</span></label>
                            <input type="number" min="1" name="return_price" class="form-control"
                                id="returnPrice" placeholder="Return Price">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Yes, Return</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script>
        const returnModal = document.getElementById('returnModal');
        const modalInvoiceId = document.getElementById('modalInvoiceId');
        const modalItemId = document.getElementById('modalItemId');
        const modalItemName = document.getElementById('modalItemName');
        const modalActualQty = document.getElementById('modalActualQty');
        const modalReturnedQty = document.getElementById('modalReturnedQty');
        const modalReturnableQty = document.getElementById('modalReturnableQty');
        const modalStatus = document.getElementById('modalStatus');
        const actionWrapper = document.getElementById('actionWrapper');
        const actionSelect = document.getElementById('actionSelect');
        const qtyWrapper = document.getElementById('qtyWrapper');
        const qtyInput = document.getElementById('returnQty');
        const priceInput = document.getElementById('returnPrice');

        const resetFields = (returnableQty, itemPrice) => {
            actionWrapper.style.display = 'block';
            actionSelect.value = '';
            qtyWrapper.style.display = 'none';
            qtyInput.value = '';
            qtyInput.removeAttribute('readonly');
            qtyInput.removeAttribute('required');
            qtyInput.setAttribute('max', returnableQty);
            actionSelect.removeAttribute('required');
            priceInput.value = itemPrice ?? '';
        };

        const setQtyField = (value, readonly = false, required = true, maxQty = null) => {
            qtyWrapper.style.display = 'block';
            qtyInput.value = value;
            readonly ? qtyInput.setAttribute('readonly', 'readonly') : qtyInput.removeAttribute('readonly');
            required ? qtyInput.setAttribute('required', 'required') : qtyInput.removeAttribute('required');
            if (maxQty) {
                qtyInput.setAttribute('max', maxQty);
            } else {
                qtyInput.removeAttribute('max');
            }
        };

        const handleActionChange = (returnableQty) => {
            const action = actionSelect.value;
            if (action === 'Return Some Qty') {
                setQtyField('', false, true, returnableQty - 1);
            } else if (action === 'Return Complete Item') {
                setQtyField(returnableQty, true, true, returnableQty);
            } else {
                qtyWrapper.style.display = 'none';
                qtyInput.value = '';
                qtyInput.removeAttribute('readonly');
                qtyInput.removeAttribute('required');
                qtyInput.removeAttribute('max');
            }
        };

        returnModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;

            const invoiceId = button.getAttribute('data-invoice-id');
            const itemId = button.getAttribute('data-item-id');
            const itemName = button.getAttribute('data-item-name');
            const itemQty = parseInt(button.getAttribute('data-item-qty')) || 0;
            const itemPrice = button.getAttribute('data-item-price');
            const itemStatus = button.getAttribute('data-item-status')?.trim();
            const partialQty = parseInt(button.getAttribute('data-item-partial-qty')) || 0;

            modalInvoiceId.value = invoiceId;
            modalItemId.value = itemId;
            modalItemName.textContent = itemName;
            modalActualQty.textContent = itemQty;
            modalReturnedQty.textContent = partialQty;
            modalStatus.textContent = itemStatus;

            // Calculate returnable qty
            let returnableQty = itemQty - partialQty;
            modalReturnableQty.textContent = returnableQty;

            resetFields(returnableQty, itemPrice);

            if (returnableQty === 1) {
                actionWrapper.style.display = 'none';
                actionSelect.value = 'Return Complete Item';
                actionSelect.removeAttribute('required');
                setQtyField(1, true, true, 1);
            } else {
                actionWrapper.style.display = 'block';
                actionSelect.setAttribute('required', 'required');
            }

            actionSelect.onchange = () => handleActionChange(returnableQty);
        });
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
