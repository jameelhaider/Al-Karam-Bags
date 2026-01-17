@extends('dashboard.master2')
@php
    $title = $account->id != null ? 'Edit Account' : 'Create New Account';
@endphp
@section('admin_title', $title)
@section('content2')
    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a href="{{ url('admin/accounts') }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-6 col-sm-8">
                    <h3 class="mt-1 d-none d-md-bloack d-lg-block" style="font-family:cursive">
                        {{ $account->id != null ? 'Edit Account' : 'Create New Account' }}</h3>

                    <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                        {{ $account->id != null ? 'Edit Account' : 'Create New Account' }}</h5>
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
                action="{{ $account->id != null ? route('update.account', ['id' => $account->id]) : route('submit.account') }}"
                method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <label for="" class="fw-bold mb-2">Customer Name<span class="text-danger">*</span></label>
                        <input type="text" placeholder="Customer Name" required
                            value="{{ old('customer_name', $account->customer_name) }}"
                            class="form-control @error('customer_name') is-invalid @enderror" name="customer_name">
                        @error('customer_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>



                    <div class="col-lg-4 col-md-4">
                        <label for="" class="fw-bold mb-2">Phone<span class="text-danger">*</span></label>

                        <input type="text" required value="{{ old('customer_phone', $account->customer_phone) }}"
                            name="customer_phone" placeholder="0300-0000000"
                            class="form-control @error('customer_phone') is-invalid @enderror"
                            data-inputmask="'mask': '0399-9999999'" type="number" maxlength = "12">

                        @error('customer_phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>






                <div class="row mt-3">
                    <div class="col-lg-6 col-md-4">
                        <label for="" class="fw-bold mb-2">Address (Optional)</label>
                        <textarea name="customer_address" class="form-control @error('customer_address') is-invalid @enderror"
                            placeholder="Customer Address" cols="30" rows="5">{{ old('customer_address', $account->customer_address ?? '') }}</textarea>

                        @error('customer_address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>





                <button type="submit" name="action" value="save" class="btn btn-primary mt-3 float-end ms-2"
                    title="Save">
                    {{ $account->id != null ? 'Update' : 'Save' }} <i class="bx bx-check-circle"></i>
                </button>

                @if ($account->id == null)
                    <button type="submit" name="action" value="save_add_new" class="btn btn-dark mt-3 float-end"
                        title="Save and Add New">
                        Save & Add New <i class="bx bx-plus-circle"></i>
                    </button>
                @endif

            </form>
        </div>
    </div>



    <script>
        $(":input").inputmask();
    </script>
@endsection
