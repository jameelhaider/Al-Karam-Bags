@extends('dashboard.master2')
@section('admin_title', 'Admin | Generate PDF')
@section('content2')
    <style>
        .card.p-2 {
            height: 300px;
        }
    </style>
    <div class="container-fluid px-3">
        <h3 class="text-center text-dark fw-bold"> DOWNLOAD PDF <i class="bx bxs-file-pdf" style="font-size: 25px"></i></h3>
        <div class="card p-3 rounded-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <h3 class="text-center text-dark fw-light">For Parts</h3>
                    <div class="p-3 rounded-0" style="border: 1px solid rgba(0, 0, 0, 0.295)">
                        <form action="{{ route('stock.parts.download.pdf') }}" method="post">
                            @csrf
                            <select name="company_id" id="company-select" required class="form-select mt-2">
                                <option value="{{ null }}">Select Company</option>
                                <option value="All">All Companies</option>
                                @foreach ($partscompanies as $partcompany)
                                    <option value="{{ $partcompany->id }}">{{ $partcompany->name }}</option>
                                @endforeach
                            </select>

                            <div class="mt-2">
                                <select name="type_id[]" id="type-select" multiple="multiple" required
                                    class="form-select mt-2">
                                    <option value="{{ null }}">Select Type</option>
                                    <option value="All">All Types</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <select name="status" required class="form-select mt-2">
                                <option value="{{ null }}">Select Status</option>
                                <option value="All">All</option>
                                <option value="Available">Available</option>
                                <option value="Available, Low Stock">Available, Low Stock</option>
                                <option value="Out Of Stock">Out Of Stock</option>
                            </select>
                            <div class="form-check mt-2">
                                <input type="checkbox" name="show_price" id="showPriceParts" class="form-check-input">
                                <label for="showPriceParts" class="form-check-label">Show Price</label>
                            </div>

                            <div class="form-check mt-2">
                                <input type="checkbox" name="show_qty" id="showQtyParts" class="form-check-input">
                                <label for="showQtyParts" class="form-check-label">Show Qty</label>
                            </div>

                            <input type="submit" value="Download PDF" class="w-100 btn btn-primary mt-2">
                        </form>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h3 class="text-center text-dark fw-light">For Tools</h3>
                    <div class="p-3 rounded-0" style="border: 1px solid rgba(0, 0, 0, 0.295);height:284px">
                        <form action="{{ route('stock.tools.download.pdf') }}" method="post">
                            @csrf
                            <select name="company_id" id="company-select2" required class="form-select mt-2">
                                <option value="{{ null }}">Select Company</option>
                                <option value="All">All Companies</option>
                                @foreach ($toolscompanies as $toolcompany)
                                    <option value="{{ $toolcompany->id }}">{{ $toolcompany->name }}</option>
                                @endforeach
                            </select>
                            <select name="status" required class="form-select mt-2">
                                <option value="{{ null }}">Select Status</option>
                                <option value="All">All</option>
                                <option value="Available">Available</option>
                                <option value="Available, Low Stock">Available, Low Stock</option>
                                <option value="Out Of Stock">Out Of Stock</option>
                            </select>
                            <div class="form-check mt-2">
                                <input type="checkbox" name="show_price" id="showPriceTools" class="form-check-input">
                                <label for="showPriceTools" class="form-check-label">Show Price</label>
                            </div>

                            <div class="form-check mt-2">
                                <input type="checkbox" name="show_qty" id="showQtyTools" class="form-check-input">
                                <label for="showQtyTools" class="form-check-label">Show Qty</label>
                            </div>
                            <input type="submit" value="Download PDF" class="w-100 btn btn-primary mt-2">
                        </form>
                    </div>
                </div>


                <div class="col-lg-4 col-md-6">
                    <h3 class="text-center text-dark fw-light">For Cash Received</h3>
                    <div class="p-3 rounded-0" style="border: 1px solid rgba(0, 0, 0, 0.295);height:284px">
                        <form action="{{ route('cash.received.download.pdf') }}" method="post">
                            @csrf
                            <select name="account_id" id="acc-select" required class="form-select mt-2">
                                <option value="{{ null }}">Select Account</option>
                                <option value="All">All Accounts</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->id . ') ' . $account->customer_name }}
                                    </option>
                                @endforeach
                            </select>

                            <input type="date" name="date" class="form-control mt-2" id="">
                            <input type="submit" value="Download PDF" class="w-100 btn btn-primary mt-2">
                        </form>
                    </div>
                </div>











            </div>

            <hr>

            <div class="row">


                <div class="col-lg-4 col-md-6">
                    <h3 class="text-center text-dark fw-light">For Parts Demands</h3>
                    <div class="p-3 rounded-0" style="border: 1px solid rgba(0, 0, 0, 0.295);height:173px">
                        <form action="{{ route('demands.download.pdf') }}" method="post">
                            @csrf
                            <input type="hidden" value="parts" name="type">
                            <select name="type_id" id="type-select2" required class="form-select mt-2">
                                <option value="">Select Type</option>
                                <option value="All">All Types</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>

                            <input type="submit" value="Download PDF" class="w-100 btn btn-primary mt-2">
                        </form>
                    </div>
                </div>



                <div class="col-lg-4 col-md-6">
                    <h3 class="text-center text-dark fw-light">For Tools Demands</h3>
                    <div class="p-3 rounded-0" style="border: 1px solid rgba(0, 0, 0, 0.295);height:173px">
                        <form action="{{ route('demands.download.pdf') }}" method="post">
                            @csrf
                            <input type="hidden" value="tools" name="type">
                            <select name="" required class="form-select mt-2">
                                <option value="">Select Option</option>
                                <option value="All">All</option>
                            </select>

                            <input type="submit" value="Download PDF" class="w-100 btn btn-primary mt-2">
                        </form>
                    </div>
                </div>




                <div class="col-lg-4 col-md-6">
                    <h3 class="text-center text-dark fw-light">For Accounts</h3>
                    <div class="p-3 rounded-0" style="border: 1px solid rgba(0, 0, 0, 0.295);height:173px">
                        <form action="{{ route('accounts.download.pdf') }}" method="post">
                            @csrf
                            <!-- Account Select -->
                            <select name="account_id" id="accountSelect" required class="form-select mt-2">
                                <option value="">Select Account</option>
                                <option value="All">All Accounts</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->id . ') ' . $account->customer_name }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Status Select (hidden by default) -->
                            <select name="status" id="statusSelect" class="form-select mt-2" style="display: none;"
                                required>
                                <option value="">Select Status</option>
                                <option value="Both">Both</option>
                                <option value="Have Some Remainings">Have Some Remainings</option>
                                <option value="Dont Have Remainings">Dont Have Remainings</option>
                            </select>

                            <input type="submit" value="Download PDF" class="w-100 btn btn-primary mt-2">
                        </form>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const accountSelect = document.getElementById('accountSelect');
                        const statusSelect = document.getElementById('statusSelect');

                        function toggleStatusVisibility() {
                            if (accountSelect.value === 'All') {
                                statusSelect.style.display = 'block';
                                statusSelect.required = true;
                            } else {
                                statusSelect.style.display = 'none';
                                statusSelect.required = false;
                                statusSelect.value = "";
                            }
                        }

                        accountSelect.addEventListener('change', toggleStatusVisibility);

                        // Run on page load (in case "All" is pre-selected)
                        toggleStatusVisibility();
                    });
                </script>


            </div>
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

        $('#company-select').select2({
            placeholder: 'Select Company',
            allowClear: true
        });
        $('#company-select2').select2({
            placeholder: 'Select Company',
            allowClear: true
        });
        $('#type-select').select2({
            placeholder: 'Select Type',
            allowClear: true
        });
        $('#type-select2').select2({
            placeholder: 'Select Type',
            allowClear: true
        });
    </script>

@endsection
