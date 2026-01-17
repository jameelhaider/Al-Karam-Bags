@php
    $layout = isset($stock->id) ? 'dashboard.master2' : 'components.stockstabs';
    $title = $stock->id ? 'Admin | Edit Stock' : 'Admin | Add New Stock';
@endphp
@extends($layout)
@section('admin_title', $title)
@section(isset($stock->id) ? 'content2' : 'content3')

    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a @if (request()->type == 'tools') href="{{ url('admin/stocks/tools') }}"
@elseif (request()->type == 'parts')
href="{{ url('admin/stocks/parts') }}" @endif
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-sm-8 col-6">
                    <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">
                        {{ $stock->id != null ? 'Edit Stock' : 'Add New Stock' }}
                    </h3>
                    <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                        {{ $stock->id != null ? 'Edit Stock' : 'Add New Stock' }}
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
                background-color: #314861;
            }

            .custom-back-button i {
                font-size: 18px;
            }
        </style>


        <div class="card p-3 mt-3">
            <form action="{{ $stock->id != null ? route('update.stock', ['id' => $stock->id]) : route('submit.stock') }}"
                method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ request()->type }}">

                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <label for="" class="fw-bold mb-2">Select Dealer<span class="text-danger">*</span></label>
                        <select name="dealer_id" id="dealer-select" class="form-select" required>
                            <option value="">Select Dealer</option>
                            @foreach ($dealers as $dealer)
                                <option value="{{ $dealer->id }}"
                                    {{ old('dealer_id', $stock->dealer_id) == $dealer->id ? 'selected' : '' }}>
                                    {{ $dealer->name . ' ( ' . $dealer->bussiness_name . ' )' }}
                                </option>
                            @endforeach
                        </select>
                        @error('dealer_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>







                </div>





                <div class="row mt-3">
                    <hr>
                    <h4 class="fw-bold mb-3">Stock Information</h4>
                    <hr>

                    <div class="col-lg-4 col-md-4 col-6">

                        <label for="" class="fw-bold mb-2">Select Company<span class="text-danger">*</span></label>
                        <select name="company_id" id="company-select" class="form-select" required>
                            <option value="{{ null }}">Select Company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ old('company_id', $stock->company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-4 col-6" id="model-select-div">
                        <label for="" class="fw-bold mb-2">Select Model<span class="text-danger">*</span></label>
                        <select name="model_id" id="model-select" class="form-select" required>
                            <option value="{{ null }}">Select Model</option>
                        </select>
                        @error('model_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>



                </div>


                <div class="row mt-3">


                    <div class="col-lg-8 col-md-4 col-6" id="model-select-div">
                        <label for="" class="fw-bold mb-2">Select
                            @if (request()->type == 'tools')
                                Tool
                            @else
                                Part
                            @endif

                            Type<span class="text-danger">*</span>
                        </label>
                        <select name="type_id" id="type-select" class="form-select" required>
                            <option value="{{ null }}">Select Type</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('type_id', $stock->type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('type_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    @if (request()->type == 'tools')
                        <div class="col-lg-8 col-md-4 col-6 mt-2">
                            <label for="" class="fw-bold mb-2">Name (Optional)</label>
                            <input type="text" placeholder="Name,Detail etc" value="{{ old('name2', $stock->name2) }}"
                                class="form-control @error('name2') is-invalid @enderror" name="name2">
                            @error('name2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @else
                        <div class="col-lg-8 col-md-4 col-6 mt-2">
                            <label for="" class="fw-bold mb-2">Other Models Supported (Optional)</label>
                            <input type="text" placeholder="Other Models Supported"
                                value="{{ old('other_models', $stock->name2) }}"
                                class="form-control @error('other_models') is-invalid @enderror" name="other_models">
                            @error('other_models')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endif



                </div>






                @if (request()->type == 'parts')
                    <div class="row mt-3">
                        <div class="col-lg-4 col-md-4 col-6">
                            <label for="" class="fw-bold mb-2">Select Quality Status<span
                                    class="text-danger">*</span></label>
                            <select name="quality_status" id="quality-select" class="form-select" required>
                                <option value="">Select Quality Status</option>
                                <option value="No Quality"
                                    {{ old('quality_status', $stock->quality_status) == 'No Quality' ? 'selected' : '' }}>
                                    No Quality</option>
                                <option value="Original"
                                    {{ old('quality_status', $stock->quality_status) == 'Original' ? 'selected' : '' }}>
                                    Original</option>
                                <option value="Copy"
                                    {{ old('quality_status', $stock->quality_status) == 'Copy' ? 'selected' : '' }}>Copy
                                </option>
                            </select>
                            @error('quality_status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-4 col-6" id="model-select-div">
                            <label for="" class="fw-bold mb-2">Select Color<span
                                    class="text-danger">*</span></label>
                            <select name="color" id="color-select" class="form-select" required>
                                <option value="">Select Color</option>
                                <option value="No Color"
                                    {{ old('color', $stock->color) == 'No Color' ? 'selected' : '' }}>No Color</option>
                                <option value="Black" {{ old('color', $stock->color) == 'Black' ? 'selected' : '' }}>
                                    Black</option>
                                <option value="White" {{ old('color', $stock->color) == 'White' ? 'selected' : '' }}>
                                    White</option>
                                <option value="Golden" {{ old('color', $stock->color) == 'Golden' ? 'selected' : '' }}>
                                    Golden</option>
                                <option value="Silver" {{ old('color', $stock->color) == 'Silver' ? 'selected' : '' }}>
                                    Silver</option>
                                <option value="Red" {{ old('color', $stock->color) == 'Red' ? 'selected' : '' }}>Red
                                </option>
                                <option value="Green" {{ old('color', $stock->color) == 'Green' ? 'selected' : '' }}>
                                    Green</option>
                                <option value="Yellow" {{ old('color', $stock->color) == 'Yellow' ? 'selected' : '' }}>
                                    Yellow</option>
                                <option value="Blue" {{ old('color', $stock->color) == 'Blue' ? 'selected' : '' }}>Blue
                                </option>
                            </select>
                            @error('color')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                @endif





                <div class="row mt-3">
                    <div class="col-lg-4 col-md-4 col-6">
                        <label for="" class="fw-bold mb-2">Quantity<span class="text-danger">*</span></label>
                        <input type="number" min="0" required placeholder="Quantity"
                            value="{{ old('qty', $stock->qty) }}" class="form-control @error('qty') is-invalid @enderror"
                            name="qty">
                        @error('qty')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-4 col-6">
                        <label for="" class="fw-bold mb-2">Alert Quantity<span
                                class="text-danger">*</span></label>
                        <input type="number" min="1" required placeholder="Alert Quantity"
                            value="{{ old('alert_qty', $stock->alert_qty) }}"
                            class="form-control @error('alert_qty') is-invalid @enderror" name="alert_qty">
                        @error('alert_qty')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>






                <div class="row mt-3">
                    <div class="col-lg-4 col-md-4 col-6">
                        <label for="" class="fw-bold mb-2">Purchase Price<span
                                class="text-danger">*</span></label>
                        <input type="number" min="0" required placeholder="Purchase Price"
                            value="{{ old('purchase_price', $stock->purchase_price) }}"
                            class="form-control @error('purchase_price') is-invalid @enderror" name="purchase_price">
                        @error('purchase_price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-4 col-6">
                        <label for="" class="fw-bold mb-2">Sale Price<span class="text-danger">*</span></label>
                        <input type="number" min="0" required placeholder="Sale Price"
                            value="{{ old('sale_price', $stock->sale_price) }}"
                            class="form-control @error('sale_price') is-invalid @enderror" name="sale_price">
                        @error('sale_price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>






                @if ($stock->l_purchase_price != null)
                    <div class="row mt-3">
                        <div class="col-lg-8 col-md-12 col-12">
                            <label for="" class="fw-bold mb-2">Last Purchasing</label>
                            <input type="number" readonly value="{{ $stock->l_purchase_price }}" class="form-control">
                        </div>
                    </div>
                @endif




                @if ($stock->id)
                    <div class="row mt-3">

                        <div class="col-lg-2 col-md-4 col-6">
                            <label for="" class="fw-bold mb-2">New Quantity (Optional)</label>
                            <input type="number" min="1" placeholder="New Quantity"
                                value="{{ old('new_qty') }}" class="form-control @error('new_qty') is-invalid @enderror"
                                name="new_qty" id="new_qty">
                            @error('new_qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-lg-3 col-md-4 col-6">
                            <label for="" class="fw-bold mb-2">New Purchase Price (Optional)</label>
                            <input type="number" min="0" placeholder="New Purchase Price"
                                value="{{ old('new_purchase_price') }}"
                                class="form-control @error('new_purchase_price') is-invalid @enderror"
                                name="new_purchase_price" id="new_purchase_price">
                            @error('new_purchase_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="col-lg-3 col-md-4 col-12 d-flex flex-column justify-content-end">
                            <label for="" class="fw-bold mb-2">Average Purchase Price</label>
                            <input type="text" class="form-control" id="avg_price" readonly>
                        </div>

                    </div>

                    <script>
                        const prevQty = {{ $stock->qty ?? 0 }};
                        const prevPrice = {{ $stock->purchase_price ?? 0 }};

                        const qtyInput = document.getElementById('new_qty');
                        const priceInput = document.getElementById('new_purchase_price');

                        qtyInput.addEventListener('input', function() {
                            toggleRequired();
                            calculateAvgPrice();
                        });

                        priceInput.addEventListener('input', function() {
                            toggleRequired();
                            calculateAvgPrice();
                        });

                        function toggleRequired() {
                            if (priceInput.value > 0) {
                                qtyInput.setAttribute('required', true);
                            } else {
                                qtyInput.removeAttribute('required');
                            }

                            if (qtyInput.value > 0) {
                                priceInput.setAttribute('required', true);
                            } else {
                                priceInput.removeAttribute('required');
                            }
                        }

                        function calculateAvgPrice() {
                            const newQty = parseFloat(qtyInput.value) || 0;
                            const newPrice = parseFloat(priceInput.value) || 0;

                            if (newQty > 0 && newPrice > 0) {
                                const prevAmount = prevQty * prevPrice;
                                const newAmount = newQty * newPrice;
                                const totalQty = prevQty + newQty;
                                const avgPrice = Math.round((prevAmount + newAmount) / totalQty);

                                document.getElementById('avg_price').value = avgPrice;
                            } else {
                                document.getElementById('avg_price').value = '';
                            }
                        }
                    </script>
                @endif




                <button type="submit" name="action" value="save" class="btn btn-primary mt-3 float-end ms-2"
                    title="Save">
                    {{ $stock->id != null ? 'Update' : 'Save' }} <i class="bx bx-check-circle"></i>
                </button>

                @if ($stock->id == null)
                    <button type="submit" name="action" value="save_add_new" class="btn btn-dark mt-3 float-end"
                        title="Save and Add New">
                        Save & Add New <i class="bx bx-plus-circle"></i>
                    </button>
                @endif

            </form>
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

    <script>
        $(":input").inputmask();
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script>
        $('#dealer-select').select2({
            placeholder: 'Select Dealer',
            allowClear: true
        });

        $('#color-select').select2({
            placeholder: 'Select Color',
            allowClear: true
        });

        $('#quality-select').select2({
            placeholder: 'Select Quality',
            allowClear: true
        });

        $('#type-select').select2({
            placeholder: 'Select Type',
            allowClear: true
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function populateModels(companyId, selectedModelId = null) {
                if (companyId) {
                    $.ajax({
                        url: '/company/' + companyId + '/models',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#model-select').empty();
                            $('#model-select').append('<option value="">Select Model</option>');

                            $.each(data, function(index, model) {
                                let selected = (model.id == selectedModelId) ? 'selected' : '';
                                $('#model-select').append('<option value="' + model.id + '" ' +
                                    selected + '>' + model.model + '</option>');
                            });
                        },
                        error: function() {
                            alert('Error fetching models. Please try again.');
                        }
                    });
                } else {
                    $('#model-select').empty();
                    $('#model-select').append('<option value="">Select Model</option>');
                }
            }

            $('#company-select').on('change', function() {
                var companyId = $(this).val();
                populateModels(companyId);
            });

            // Initial population on page load for edit
            var initialCompanyId = $('#company-select').val();
            var initialModelId = '{{ old('model_id', $stock->model_id ?? '') }}';
            if (initialCompanyId) {
                populateModels(initialCompanyId, initialModelId);
            }
        });
    </script>


@endsection
