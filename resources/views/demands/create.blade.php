@extends('dashboard.master2')
@php
    $title = $demand->id != null ? 'Edit Demand' : 'Add New Demand';
@endphp
@section('admin_title', $title)
@section('content2')
    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a href="{{ url('admin/demands/' . request()->type) }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-6 col-sm-8">
                    <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">
                        {{ $demand->id != null ? 'Edit Demand' : 'Add New Demand' }}</h3>

                    <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                        {{ $demand->id != null ? 'Edit Demand' : 'Add New Demand' }}</h5>
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
            <form
                action="{{ $demand->id != null ? route('update.demand', ['id' => $demand->id]) : route('submit.demand') }}"
                method="POST">
                @csrf
                <input type="hidden" value="{{ request()->type }}" name="type">
                <div class="row">

                    <div class="col-lg-4 col-md-4">
                        <label for="" class="fw-bold mb-2">Demand Name <span class="text-danger">*</span></label>
                        <input type="text" required placeholder="Demand Name" value="{{ $demand->name }}"
                            class="form-control @error('name') is-invalid @enderror" name="name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    @if (request()->type == 'parts')
                        <div class="col-lg-4 col-md-4">
                            <label for="" class="fw-bold mb-2">Select Type <span class="text-danger">*</span></label>
                            <select name="item_type_id" required
                                class="form-select @error('item_type_id') is-invalid @enderror" id="type-select">
                                <option value="{{ null }}">Select Type</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}"
                                        {{ $demand->item_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('item_type_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endif


                    <div class="col-lg-4 col-md-4">
                        <label for="" class="fw-bold mb-2">Qty (Optional)</label>
                        <input type="number" placeholder="Qty" min="1" value="{{ $demand->qty }}"
                            class="form-control @error('qty') is-invalid @enderror" name="qty">
                        @error('qty')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                </div>







                <button type="submit" name="action" value="save" class="btn btn-primary mt-3 float-end ms-2"
                    title="Save">
                    {{ $demand->id != null ? 'Update' : 'Save' }} <i class="bx bx-check-circle"></i>
                </button>

                @if ($demand->id == null)
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script>
        $('#type-select').select2({
            placeholder: 'Select Type',
             allowClear: true
        });
    </script>
@endsection
