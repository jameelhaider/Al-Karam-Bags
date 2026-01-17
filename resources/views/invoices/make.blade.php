@extends('components.invoicetabs')
@section('admin_title', 'Admin | Make Sale Invoice')
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
                        <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family: cursive;">Make Sale Invoice</h3>
                        <h5 class="mt-1 d-block d-sm-block d-lg-none d-md-none" style="font-family: cursive;">Make Sale
                            Invoice
                        </h5>
                        <div class="ms-4 d-none d-lg-block">
                            <span id="togglePurchasePriceButton" style="cursor: pointer;color:#2596be"
                                onclick="togglePriceField()">
                                <i class="bx bx-show"></i> Show Purchase Price
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-2 p-2 mt-2 sticky-top">
            <form action="" method="GET">
                <div class="row">
                    <div class="col-lg-9 col-md-8 col-sm-8 col-12 mt-1 mb-1">
                        <input type="text" class="form-control" value="{{ request()->name }}" placeholder="Name"
                            name="name">
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-4 col-12 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ url('admin/invoice/make') }}" title="Clear"
                                class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <style>
            .sticky-top {
                position: sticky;
                top: 0;
                z-index: 1000;
                background-color: #fff;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.49);
                border-bottom: 1px solid #ddd;
            }
        </style>
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
        <form action="{{ route('save.invoice') }}" id="invoiceForm" method="post">
            @csrf
            {{-- School Bags --}}
            @include('partials.invoice_table', [
                'items' => $schoolbags,
                'title' => 'School Bags',
                'showPurchase' => true,
            ])

            {{-- Hand Carries --}}
            @include('partials.invoice_table', [
                'items' => $handcarries,
                'title' => 'Hand Carries',
                'showPurchase' => true,
            ])
            {{-- Travel Bags --}}
            @include('partials.invoice_table', [
                'items' => $travelbags,
                'title' => 'Travel Bags',
                'showPurchase' => true,
            ])
            {{-- Hand Bags --}}
            @include('partials.invoice_table', [
                'items' => $handbags,
                'title' => 'Hand Bags',
                'showPurchase' => true,
            ])


            <div class="card p-2 mt-2 sticky-bottom">
                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                        <select name="account_id" class="form-select form-select-lg" id="accountIdInput">
                            <option value="">Select Account</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->id . ') ' . $account->customer_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                        <select name="status" required class="form-select form-select-lg" id="statusSelect">
                            <option value="">Select Status</option>
                            <option value="Paid">Paid</option>
                            <option value="Un Paid">Un Paid</option>
                            <option value="Blank">Blank</option>
                        </select>
                    </div>


                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <h2 class="text-end mt-2 mb-2 fw-bold text-dark">Total Bill: <span id="total_bill">Rs.0</span>
                        </h2>
                    </div>
                </div>
                <input type="submit" value="Make Invoice" class="btn btn-primary w-100" id="makeInvoiceBtn" disabled>


                <a class="btn btn-primary mt-2" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
                    aria-controls="offcanvasExample">
                    Create Quick Account
                </a>
            </div>


            <style>
                .select2-container--default .select2-selection--single {
                    height: 48px;
                    padding: 8px 12px;
                    font-size: 1.0rem;
                    border: 1px solid #ced4da;
                    border-radius: 0.40rem;
                }

                .select2-container--default .select2-selection--single .select2-selection__arrow {
                    height: 46px;
                    right: 8px;
                }
            </style>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
            <script>
                $('#accountIdInput').select2({
                    placeholder: 'Select Account',
                    width: 'resolve'
                });
            </script>

        </form>





















        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Create Quick Account</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form action="{{ route('submit.account') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12 col-sm-12 mt-2">
                            <label for="" class="fw-bold mb-2">Customer Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" placeholder="Customer Name" required value="{{ old('customer_name') }}"
                                class="form-control @error('customer_name') is-invalid @enderror" name="customer_name">
                            @error('customer_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="col-lg-12 col-md-12 col-12 col-sm-12 mt-2">
                            <label for="customer_phone" class="fw-bold mb-2">Phone<span class="text-danger">*</span></label>
                            <input type="text" id="customer_phone" required value="{{ old('customer_phone') }}"
                                name="customer_phone" placeholder="0300-0000000"
                                class="form-control @error('customer_phone') is-invalid @enderror" maxlength="12">

                            @error('customer_phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const phoneInput = document.getElementById("customer_phone");

                                phoneInput.addEventListener("input", function() {
                                    let value = phoneInput.value.replace(/\D/g, ""); // remove non-digits

                                    // Ensure it starts with 0
                                    if (value.length > 0 && value[0] !== "0") {
                                        value = "0" + value;
                                    }

                                    // Apply mask: 0300-1234567
                                    if (value.length > 4) {
                                        value = value.slice(0, 4) + "-" + value.slice(4);
                                    }

                                    // Limit to 12 characters (XXXX-XXXXXXX)
                                    if (value.length > 12) {
                                        value = value.slice(0, 12);
                                    }

                                    phoneInput.value = value;
                                });
                            });
                        </script>


                        <div class="col-lg-12 col-md-12 col-12 col-sm-12 mt-2">
                            <label for="" class="fw-bold mb-2">Address (Optional)</label>
                            <textarea name="customer_address" class="form-control @error('customer_address') is-invalid @enderror"
                                placeholder="Customer Address" cols="30" rows="5">{{ old('customer_address') }}</textarea>

                            @error('customer_address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" name="action" value="save_add_new" class="btn btn-primary mt-3 float-end"
                        title="Save">
                        Save <i class="bx bx-check-circle"></i>
                    </button>

                </form>
            </div>
        </div>



        <script>
            const accountIdInput = document.getElementById('accountIdInput');
            const statusSelect = document.getElementById('statusSelect');
            const makeInvoiceBtn = document.getElementById('makeInvoiceBtn');

            function checkFormValidity() {
                const isAccountSelected = accountIdInput.value.trim() !== '';
                const isStatusSelected = statusSelect.value.trim() !== '';
                makeInvoiceBtn.disabled = !(isAccountSelected && isStatusSelected);
            }
            accountIdInput.addEventListener('change', checkFormValidity);
            statusSelect.addEventListener('change', checkFormValidity);
        </script>

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
            document.getElementById('makeInvoiceBtn').addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default form submission

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to create this invoice?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, make it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form programmatically
                        document.getElementById('invoiceForm').submit();
                    }
                });
            });
        </script>
        <script>
            function calculateTotalBill() {
                let totalBill = 0;
                document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                    const id = checkbox.name.match(/items\[(\d+)\]\[selected\]/)[1];
                    if (checkbox.checked) {
                        const qty = parseFloat(document.querySelector(`input[name='items[${id}][qty]']`).value) || 0;
                        const finalPrice = parseFloat(document.querySelector(`input[name='items[${id}][final_price]']`)
                            .value) || 0;
                        totalBill += qty * finalPrice;
                    }
                });
                document.getElementById('total_bill').textContent = 'Rs.' + totalBill.toFixed(2);
            }
            document.addEventListener('input', function(event) {
                if (event.target.classList.contains('qty') || event.target.classList.contains('final_price')) {
                    const id = event.target.dataset.id;
                    const isSelected = document.querySelector(`input[name='items[${id}][selected]']`).checked;
                    if (isSelected) {
                        const qty = document.querySelector(`input[name='items[${id}][qty]']`).value;
                        const finalPrice = document.querySelector(`input[name='items[${id}][final_price]']`).value;
                        const total = (qty * finalPrice);
                        document.getElementById(`total_${id}`).textContent = 'Rs.' + total;
                    } else {
                        const qtyField = document.querySelector(`input[name='items[${id}][qty]']`);
                        const finalPriceField = document.querySelector(`input[name='items[${id}][final_price]']`);
                        qtyField.value = 1; // Default value
                        finalPriceField.value = finalPriceField.min; // Reset to minimum price
                        document.getElementById(`total_${id}`).textContent = 'Rs.' + finalPriceField
                            .min;
                    }
                    calculateTotalBill();
                }
            });
            document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    calculateTotalBill();
                });
            });
            window.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                    const id = checkbox.name.match(/items\[(\d+)\]\[selected\]/)[1];
                    const qtyField = document.querySelector(`input[name='items[${id}][qty]']`);
                    const finalPriceField = document.querySelector(`input[name='items[${id}][final_price]']`);
                    qtyField.disabled = !checkbox.checked;
                    finalPriceField.disabled = !checkbox.checked;
                    checkbox.addEventListener('change', function() {
                        qtyField.disabled = !this.checked;
                        finalPriceField.disabled = !this.checked;
                    });
                });
                calculateTotalBill(); // Initial calculation
            });
        </script>
        <script>
            function togglePriceField() {
                const toggleButton = document.getElementById('togglePurchasePriceButton');
                const isCurrentlyVisible = toggleButton.getAttribute('data-visible') === 'true';
                const isVisible = !isCurrentlyVisible;
                localStorage.setItem('showPurchasePrice2', isVisible);
                toggleButton.setAttribute('data-visible', isVisible);
                toggleButton.innerHTML = isVisible ?
                    '<i class="bx bx-hide"></i> Hide Purchase Price' :
                    '<i class="bx bx-show"></i> Show Purchase Price';
                updatePriceVisibility(isVisible);
            }

            function updatePriceVisibility(isVisible) {
                const tables = document.querySelectorAll('table'); // Get all tables
                tables.forEach(table => {
                    const priceHeader = table.querySelector('.purchase-price-header');
                    const priceCells = table.querySelectorAll('.purchase-price-cell');

                    if (priceHeader) {
                        priceHeader.style.display = isVisible ? '' : 'none';
                    }

                    priceCells.forEach(cell => {
                        cell.style.display = isVisible ? '' : 'none';
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                const savedState = localStorage.getItem('showPurchasePrice2');
                const isVisible = savedState === 'true';

                const toggleButton = document.getElementById('togglePurchasePriceButton');
                toggleButton.setAttribute('data-visible', isVisible);
                toggleButton.innerHTML = isVisible ?
                    '<i class="bx bx-hide"></i> Hide Purchase Price' :
                    '<i class="bx bx-show"></i> Show Purchase Price';

                updatePriceVisibility(isVisible);
            });
        </script>
    </div>
@endsection
