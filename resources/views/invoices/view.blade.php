@extends('dashboard.master2')
@php
    $title = 'View Sale Invoice | ' . $invoice->invoice_id;
@endphp
@section('admin_title', $title)
@section('content2')

    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-3 col-sm-2">
                    <a href="{{ route('index.invoice') }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-8 col-6 col-sm-8">
                    <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">View Sale Invoice |
                        {{ $invoice->invoice_id }}
                    </h3>
                    <small class="mt-1 d-block text-center d-md-none" style="font-family:cursive">View Sale Invoice |
                        {{ $invoice->invoice_id }}
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
                background-color: #314861;
            }

            .custom-back-button i {
                font-size: 18px;
            }
        </style>





        <div class="row justify-content-center">
            <div class="col-lg-8 col-12 col-md-10">

                <div class="card mt-2 shadow p-1" style="border-radius: 0%">
                    <div class="d-flex justify-content-between">
                        <a href="javascript:void(0);" class="btn btn-sm me-2"
                            style="background-color: rgb(241, 61, 70);color:white"
                            onclick="copyToClipboard('{{ $invoice->id }}')">
                            Copy Invoice No <i class="bx bx-copy"></i>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-sm me-2"
                            style="background-color: rgb(155, 64, 143);color:white"
                            onclick="copyToClipboard('{{ $invoice->invoice_id }}')">
                            Copy Invoice ID <i class="bx bx-copy"></i>
                        </a>
                        <a href="{{ url('/admin/stocks/parts') }}" target="_BLANK" class="btn btn-sm me-2"
                            style="background-color: rgb(30, 176, 30);color:white">Add Parts <i class="bx bx-plus"></i></a>
                        <a href="{{ url('/admin/stocks/tools') }}" target="_BLANK" class="btn btn-sm me-2"
                            style="background-color: rgb(22, 128, 151);color:white">Add Tools <i class="bx bx-plus"></i></a>

                        <button id="downloadImage" class="btn btn-sm btn-dark me-2">Download as Image <i
                                class="bx bx-image"></i></button>
                        <button id="downloadPDF" class="btn btn-sm btn-primary">Download as PDF <i
                                class=" bx bxs-file-pdf"></i></button>
                    </div>
                </div>
                <script>
                    function copyToClipboard(text) {
                        navigator.clipboard.writeText(text).then(function() {
                            alert('Copied: ' + text);
                        }, function(err) {
                            console.error('Failed to copy: ', err);
                        });
                    }
                </script>
                <script>
                    // Function to ignore elements with class 'not-show'
                    function ignoreNotShowElements(element) {
                        return element.classList && element.classList.contains('not-show');
                    }

                    // Download as Image
                    document.getElementById("downloadImage").addEventListener("click", function() {
                        const receipt = document.getElementById("receipt-card");

                        html2canvas(receipt, {
                            ignoreElements: ignoreNotShowElements
                        }).then(canvas => {
                            const link = document.createElement('a');
                            link.download = 'sale-invoice.png';
                            link.href = canvas.toDataURL("image/png");
                            link.click();
                        });
                    });

                    // Download as PDF
                    document.getElementById("downloadPDF").addEventListener("click", function() {
                        const {
                            jsPDF
                        } = window.jspdf;
                        const receipt = document.getElementById("receipt-card");

                        html2canvas(receipt, {
                            scale: 2,
                            ignoreElements: ignoreNotShowElements
                        }).then(canvas => {
                            const imgData = canvas.toDataURL("image/png");

                            const pdf = new jsPDF("p", "mm", "a5");
                            const pdfWidth = pdf.internal.pageSize.getWidth();
                            const pdfHeight = pdf.internal.pageSize.getHeight();

                            const imgWidth = pdfWidth;
                            const imgHeight = (canvas.height * imgWidth) / canvas.width;

                            let heightLeft = imgHeight;
                            let position = 0;

                            // Add first page
                            pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                            heightLeft -= pdfHeight;

                            // Add more pages if needed
                            while (heightLeft > 0) {
                                position = heightLeft - imgHeight;
                                pdf.addPage();
                                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                                heightLeft -= pdfHeight;
                            }

                            pdf.save("sale-invoice.pdf");
                        });
                    });
                </script>




                <div class="card p-4 mt-2" id="receipt-card"
                    style="border-radius: 25px;background-color:rgb(255, 255, 255)">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-4">
                            <img src="{{ asset('uploads/c2.png') }}" class="img-fluid" height="300px" alt="">
                        </div>
                        <div class="col-lg-9 col-md-9 col-12">
                            <span class="fw-bold text-dark" style="font-size:3em">Al-Karam Bags </span>

                            <div class="p-2">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-6">
                                        <h5 class="fw-bold text-dark">Address</h5>
                                        <h6 class="text-dark">-Shop # FF/340 D-Point Plaza GT Road, Gujranwala</h6>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-6">
                                        <h5 class="fw-bold text-dark">Conatct</h5>
                                        <span class="h5 fw-bold text-dark">Husnain Nasir:</span> <span
                                            class="h6 text-dark">0337-4967077
                                        </span>
                                        <br>
                                        <span class="h5 fw-bold text-dark">Nasir Ahmad:</span> <span
                                            class="h6 text-dark">0305-5760932</span>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                    <div class="mt-2" style="background-color: rgba(0, 0, 0, 0.64);height:2px"></div>

                    <h2 class="text-dark text-center fw-bold mt-2">Stock Sale Invoice</h2>
                    <div style="background-color: rgba(0, 0, 0, 0.64);height:2px"></div>


                    <style>
                        .table-borderless-yellow tbody tr {
                            border-bottom: 1px solid rgba(0, 0, 0, 0.286);
                        }

                        .table-borderless-yellow thead tr {
                            border-bottom: 2px solid rgba(0, 0, 0, 0.64);
                        }

                        .table-borderless-yellow td,
                        .table-borderless-yellow th {
                            border: none !important;
                        }
                    </style>
                    <div class="table-responsive">
                        <table class="table table-borderless-yellow">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-dark fw-bold" style="font-size:14px">#</th>
                                    <th scope="col" class="text-dark fw-bold" style="font-size:14px">Name</th>
                                    <th scope="col" class="text-dark fw-bold text-center" style="font-size:14px">
                                        Qty</th>
                                    <th scope="col" class="text-dark fw-bold" style="font-size:14px">Price</th>
                                    <th scope="col" class="text-dark fw-bold" style="font-size:14px">Total</th>
                                    <th scope="col" class="fw-bold text-dark not-show" style="font-size:14px">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalQty = 0; @endphp
                                @foreach ($invoice_items as $key => $item)
                                    <tr>
                                        <th class="text-dark text-dark" scope="row">{{ ++$key }}</th>
                                        <td class="text-dark">{{ $item->name }}</td>
                                        <td class="text-center text-dark">{{ $item->qty }}</td>
                                        <td class="text-dark">{{ 'Rs.' . number_format($item->final_price) }}</td>
                                        <td class="text-dark">{{ 'Rs.' . number_format($item->total) }}</td>

                                        <td class="not-show">
                                            <div class="dropdown ms-auto">
                                                <button class="btn btn-dark btn-sm dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="dropdownMenuButton">

                                                    {{-- Return option --}}
                                                    @if ($item->status != 'Returned')
                                                        <li>
                                                            <a href="#" class="dropdown-item"
                                                                data-bs-toggle="modal" data-bs-target="#returnModal"
                                                                data-invoice-id="{{ $invoice->id }}"
                                                                data-item-id="{{ $item->item_id }}"
                                                                data-item-name="{{ $item->name }}"
                                                                data-item-qty="{{ $item->qty }}"
                                                                data-item-status="{{ $item->status }}"
                                                                data-item-partial-qty="{{ $item->partial_qty ?? 0 }}"
                                                                data-item-price="{{ $item->final_price }}"
                                                                title="Use this if customer returns an item later. It will create a returned invoice.">
                                                                <i class='bx bx-undo'></i> Return
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li class="text-primary">
                                                            <a href="javascript:void(0);" class="dropdown-item">Returned
                                                            </a>
                                                        </li>
                                                    @endif


                                                    @php
                                                        $createdTime = $item->created_at ?? $invoice->created_at;
                                                        $canRemoveTime = now()->diffInHours($createdTime) < 24;
                                                    @endphp

                                                    @if ($item->status != 'Returned')
                                                        @if ($canRemoveTime)
                                                            <li>
                                                                <a href="javascript:void(0);"
                                                                    class="dropdown-item remove-item-btn"
                                                                    data-href="{{ route('remove.invoice.item', ['id' => $item->item_id, 'invoice_id' => $invoice->id]) }}"
                                                                    title="Use this if the item was added by mistake. It won’t create return history.">
                                                                    <i class='bx bx-x'></i> Remove
                                                                </a>
                                                            </li>
                                                        @else
                                                            <li class="text-warning">
                                                                <a href="javascript:void(0);" class="dropdown-item">
                                                                    Removal disabled (Time limit exceeded)
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @else
                                                        <li class="text-danger">
                                                            <a href="javascript:void(0);" class="dropdown-item">
                                                                Removal disabled (Item Already Returned)
                                                            </a>
                                                        </li>
                                                    @endif



                                                </ul>
                                            </div>
                                        </td>







                                    </tr>
                                    @php $totalQty += $item->qty; @endphp
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td class="fw-bold text-dark">Total Qty</td>
                                    <td class="text-center text-dark">{{ $totalQty }}</td>
                                    <td colspan="1" class="fw-bold text-dark" style="width: 15%">Total Bill</td>
                                    <td class="text-dark">{{ 'Rs.' . number_format($invoice->total_bill) }}</td>
                                    <td class="not-show"></td>
                                </tr>

                                @if ($invoice->account_id != '1')
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="1" class="fw-bold text-dark" style="width: 15%">Prev Balance
                                        </td>
                                        <td class="text-dark">{{ 'Rs.' . number_format($invoice->prev_balance) }}</td>
                                        <td class="not-show"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="1" class="fw-bold text-dark" style="width: 15%">Net Balance</td>
                                        <td class="text-dark">
                                            {{ 'Rs.' . number_format($invoice->prev_balance + $invoice->total_bill) }}
                                        </td>
                                        <td class="not-show"></td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>


                    @if ($invoice->account_id != '1')
                        <label class="text-dark mt-2"><strong>Total In Words: </strong>
                            {{ numberToWords($invoice->total_bill) }}</label>
                        <label class="text-dark"><strong>Prev Balance In Words: </strong>
                            {{ numberToWords($invoice->prev_balance) }}</label>
                        <label class="text-dark"><strong>Net Balance In Words: </strong>
                            {{ numberToWords($invoice->prev_balance + $invoice->total_bill) }}</label>
                    @else
                        <label class="text-dark mt-2"><strong>Total In Words: </strong>
                            {{ numberToWords($invoice->total_bill) }}</label>
                    @endif




                    <div class="d-flex justify-content-between">
                        @if ($invoice->invoice_to)
                            <div>
                                <h2 class="mt-3 fw-bold text-dark">Invoice To:</h2>
                                <h3 class="fw-light text-dark">{{ $invoice->invoice_to }}</h3>
                            </div>
                        @endif

                        <div class="d-block">
                            <h2 class="mt-3 fw-bold d-none">Invoice To:</h2>
                            <h3 class="fw-light d-none text-dark">{{ $invoice->invoice_to }}</h3>
                        </div>

                        <div class="float-end">
                            @if ($invoice->status == 'Paid' || $invoice->status == null)
                                <img src="{{ asset('uploads/paidstump.png') }}" height="200px" width="200px"
                                    alt="">
                            @elseif ($invoice->status == 'Un Paid')
                                <img src="{{ asset('uploads/unpaid.png') }}" height="200px" width="200px"
                                    alt="">
                            @endif


                        </div>
                    </div>




                    <div class="row mt-3">
                        <hr style="border-top: 1px solid rgba(0, 0, 0, 0.286)">
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="h6 text-dark"><strong class="text-dark">Sale Invoice No:</strong>
                                    {{ $invoice->id }}</span>

                            </div>
                            <div>
                                <span class="h6 float-end text-dark"><strong class="text-dark">Account ID:</strong>
                                    {{ $invoice->account_id }}</span>
                            </div>
                        </div>
                        <hr style="border-top: 1px solid rgba(0, 0, 0, 0.286)">

                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="h6 text-dark">
                                    <strong class="text-dark">Sale Date:</strong>
                                    {{ \Carbon\Carbon::parse($invoice->created_at)->format('F d, Y h:i A') }}
                                </span>


                            </div>
                            <div>
                                <span class="h6 float-end text-dark"><strong class="text-dark">Sale Invoice ID:</strong>
                                    {{ $invoice->invoice_id }}</span>
                            </div>
                        </div>
                        <hr style="border-top: 1px solid rgba(0, 0, 0, 0.286)">
                        <h4 class="text-center fw-bold text-dark">Thank You For Bussiness With Us</h4>
                        <hr style="border-top: 1px solid rgba(0, 0, 0, 0.286)">
                    </div>

                </div>


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
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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
                                <label for="returnQty" class="fw-bold">Return Qty <span
                                        class="text-danger">*</span></label>
                                <input type="number" min="1" name="return_qty" class="form-control"
                                    id="returnQty" placeholder="Qty To Return">
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






        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".remove-item-btn").forEach(function(btn) {
                    btn.addEventListener("click", function(e) {
                        e.preventDefault();
                        let url = this.getAttribute("data-href");

                        Swal.fire({
                            title: "Are you sure?",
                            text: "This will permanently remove the item from the invoice!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Yes, remove it!",
                            cancelButtonText: "Cancel"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = url;
                            }
                        });
                    });
                });
            });
        </script>







    </div>






@endsection
