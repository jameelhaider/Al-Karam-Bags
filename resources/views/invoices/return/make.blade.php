@extends('components.invoicetabs')
@section('admin_title', 'Admin | Make Return Invoice')
@section('content4')
    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-6 col-md-3 col-sm-4">
                    <a href="{{ url('/admin') }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-home-circle me-2"></i> Dashboard
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-9 col-sm-8">

                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family: cursive;">Make Return Invoice</h3>
                        <h5 class="mt-1 d-block d-sm-block d-lg-none d-md-none" style="font-family: cursive;">Make Return
                            Invoice
                        </h5>
                    </div>


                </div>
            </div>
        </div>





        <style>
            .large-checkbox {
                transform: scale(1.5);
                -webkit-transform: scale(1.5);
            }

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
                        <select name="acc_id" id="acc-select" class="form-select" onchange="this.form.submit()">
                            <option value="">Select Account</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ request()->acc_id == $account->id ? 'selected' : '' }}>
                                    {{ $account->id . ') ' . $account->customer_name }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-4 col-12 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ url('admin/invoice/make/return') }}" title="Clear"
                                class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>











        <form action="{{ route('save.invoice.return') }}" method="POST">
            @csrf

            @if ($items != null)
                <div class="card p-2 mb-0">
                    @if ($items->count() > 0)
                        <div class="table-responsive">
                            <table class="table" id="returnTable">
                                <thead>
                                    <tr>
                                        <th class="text-dark fw-bold text-center">#</th>
                                        <th class="text-dark fw-bold" style="width: 30%">Name</th>
                                        <th class="text-dark fw-bold" style="width: 20%">Status | Qty(s)</th>
                                        <th class="text-dark fw-bold" style="width: 17%">Total Sale</th>
                                        <th class="text-dark fw-bold" style="width: 15%">Return Qty | Price</th>
                                        <th class="text-dark fw-bold" style="width: 17%">Total Return</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $key => $item)
                                        <tr>
                                            <td class="text-center">
                                                <label>
                                                    <input type="checkbox" id="checkbox-{{ $item->id }}"
                                                        name="items[{{ $item->id }}][selected]" value="1"
                                                        class="large-checkbox">
                                                </label>
                                            </td>
                                            <td class="text-dark">
                                                <label for="checkbox-{{ $item->id }}" class="fw-bold">
                                                    {{ $item->stock_name }}
                                                </label>
                                            </td>
                                            <td class="text-dark">
                                                <span>Status: <strong>{{ $item->status }}</strong></span>
                                                @if ($item->status == 'Partial Returned')
                                                    <br>
                                                    <span>Actual Qty: <strong>{{ $item->stock_qty }}</strong></span>
                                                    <br>
                                                    <span>Already Returned:
                                                        <strong>{{ $item->partial_qty }}</strong></span>
                                                    <br>
                                                    <span>Returnable:
                                                        <strong>{{ $item->stock_qty - $item->partial_qty }}</strong></span>
                                                @endif
                                            </td>
                                            <td class="text-dark">
                                                {{ $item->stock_qty . ' x ' . number_format($item->price) . ' = Rs. ' . number_format($item->total) }}
                                            </td>
                                            <td>
                                                @if ($item->status == 'Partial Returned')
                                                    <div class="input-group input-group-sm mb-1">
                                                        <span class="input-group-text bg-light">Qty</span>
                                                        <input type="number" class="form-control return-qty"
                                                            name="items[{{ $item->id }}][qty]"
                                                            data-id="{{ $item->id }}"
                                                            max="{{ $item->stock_qty - $item->partial_qty }}"
                                                            min="1" value="1" disabled>
                                                    </div>
                                                @else
                                                    <div class="input-group input-group-sm mb-1">
                                                        <span class="input-group-text bg-light">Qty</span>
                                                        <input type="number" class="form-control return-qty"
                                                            name="items[{{ $item->id }}][qty]"
                                                            data-id="{{ $item->id }}" max="{{ $item->stock_qty }}"
                                                            min="1" value="1" disabled>
                                                    </div>
                                                @endif


                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-light">Price</span>
                                                    <input type="number" class="form-control return-price"
                                                        name="items[{{ $item->id }}][price]"
                                                        data-id="{{ $item->id }}" value="{{ $item->price }}"
                                                        disabled>
                                                </div>
                                            </td>
                                            <td class="fw-bold text-dark total-return"
                                                id="total-return-{{ $item->id }}">
                                                1 x {{ number_format($item->price) }} = Rs.
                                                {{ number_format($item->price) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-dark fw-bold text-center">#</th>
                                        <th class="text-dark fw-bold">Name</th>
                                        <th class="text-dark fw-bold">Total Sale</th>
                                        <th class="text-dark fw-bold">Return Qty | Price</th>
                                        <th class="text-dark fw-bold">Total Return</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <h4 class="h4 text-center fw-normal text-muted mt-2">No Data Found!</h4>
                    @endif
                </div>
            @endif

            <input type="hidden" name="acc_id" value="{{ request()->acc_id }}">
            <input type="hidden" name="total_return" id="total_return" value="0">

            <div class="card p-2 mt-2 sticky-bottom">
                <div class="row mb-2">
                    <div class="col-lg-12">
                        <h2 class="text-end mt-2 mb-2 fw-bold text-dark">
                            Total Return Bill: <span id="total_return_bill">Rs.0</span>
                        </h2>
                    </div>
                </div>
                <input type="submit" value="Make return Invoice" class="btn btn-primary w-100"
                    @if (!request()->acc_id) disabled @endif>
            </div>
        </form>



        <style>
            .sticky-bottom {
                position: sticky;
                bottom: 0;
                z-index: 1000;
                background-color: #fff;
                box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
                border-top: 1px solid #ddd;
            }
        </style>




        <script>
            document.addEventListener("DOMContentLoaded", function() {
                function formatNumber(num) {
                    return Math.round(num).toLocaleString();
                }

                function calculateTotalReturn(id) {
                    let qtyInput = document.querySelector(`.return-qty[data-id='${id}']`);
                    let priceInput = document.querySelector(`.return-price[data-id='${id}']`);

                    let qty = parseFloat(qtyInput?.value) || 1;
                    let price = parseFloat(priceInput?.value) || 0;
                    let total = qty * price;

                    let totalCell = document.getElementById(`total-return-${id}`);
                    if (totalCell) {
                        totalCell.innerText = qty + " x " + formatNumber(price) + " = Rs. " + formatNumber(total);
                    }
                    updateGrandTotal();
                }

                // grand total
                function updateGrandTotal() {
                    let grandTotal = 0;
                    let checkedCount = 0;

                    document.querySelectorAll("input[type='checkbox'][id^='checkbox-']").forEach(checkbox => {
                        if (checkbox.checked) {
                            checkedCount++;
                            let id = checkbox.id.replace("checkbox-", "");
                            let qty = parseFloat(document.querySelector(`.return-qty[data-id='${id}']`)
                                ?.value) || 1;
                            let price = parseFloat(document.querySelector(`.return-price[data-id='${id}']`)
                                ?.value) || 0;
                            grandTotal += qty * price;
                        }
                    });

                    document.getElementById("total_return_bill").innerText = "Rs." + formatNumber(grandTotal);
                    document.getElementById("total_return").value = grandTotal;
                    let submitBtn = document.querySelector("input[type='submit']");
                    if (checkedCount > 0) {
                        submitBtn.removeAttribute("disabled");
                    } else {
                        submitBtn.setAttribute("disabled", true);
                    }
                }
                document.querySelectorAll(".return-qty, .return-price").forEach(input => {
                    input.addEventListener("input", function() {
                        let id = this.getAttribute("data-id");
                        calculateTotalReturn(id);
                    });
                });
                document.querySelectorAll("input[type='checkbox'][id^='checkbox-']").forEach(checkbox => {
                    checkbox.addEventListener("change", function() {
                        let id = this.id.replace("checkbox-", "");
                        let qtyInput = document.querySelector(`.return-qty[data-id='${id}']`);
                        let priceInput = document.querySelector(`.return-price[data-id='${id}']`);

                        if (this.checked) {
                            qtyInput.removeAttribute("disabled");
                            priceInput.removeAttribute("disabled");
                            if (!qtyInput.value || parseFloat(qtyInput.value) <= 0) {
                                qtyInput.value = 1;
                            }

                            calculateTotalReturn(id);
                        } else {
                            qtyInput.setAttribute("disabled", true);
                            priceInput.setAttribute("disabled", true);
                            calculateTotalReturn(id);
                        }
                        updateGrandTotal();
                    });
                });
                updateGrandTotal();
            });
        </script>






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
