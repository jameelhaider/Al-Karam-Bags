{{-- @php
    $layout = isset($stock->id) ? 'dashboard.master2' : 'components.stockstabs';
    $title = $stock->id ? 'Admin | Edit Stock' : 'Admin | Add New Stock';
@endphp
@extends($layout)
@section('admin_title', $title)
@section(isset($stock->id) ? 'content2' : 'content3') --}}

@php
    $action = request()->routeIs('stock.edit') ? 'Edit Stock' : 'Add New Stock';

    $title = match (request()->type) {
        'handcarries' => "$action | Hand Carries",
        'handbags'    => "$action | Hand Bags",
        'schoolbags'  => "$action | School Bags",
        'travelbags'  => "$action | Travel Bags",
        default       => 'Title Not Found',
    };
@endphp

@extends('dashboard.master2')
@section('admin_title', $title)
@section('content2')



    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a href="{{ route('stock.index',['type'=>request()->type]) }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-sm-8 col-6">
                    <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">
                        {{ $title }}
                    </h3>
                    <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                        {{ $title }}
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

                <div class="row mt-3">
                    <hr>
                    <h4 class="fw-bold mb-3">Stock Information</h4>
                    <hr>

                </div>



 <div class="row">
                    <div class="col-lg-8 col-md-8 col-12">
                        <label for="" class="fw-bold mb-2">Name<span class="text-danger">*</span></label>
                        <input type="text" required placeholder="Name"
                            value="{{ old('name', $stock->name) }}" class="form-control @error('name') is-invalid @enderror"
                            name="name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>



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
                        <input type="number" min="1" required placeholder="Purchase Price"
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
                        <input type="number" min="2" required placeholder="Sale Price"
                            value="{{ old('sale_price', $stock->sale_price) }}"
                            class="form-control @error('sale_price') is-invalid @enderror" name="sale_price">
                        @error('sale_price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

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
