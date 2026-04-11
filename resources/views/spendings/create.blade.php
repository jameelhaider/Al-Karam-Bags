@extends('dashboard.master2')
@php
    $title = $spending->id != null ? 'Edit Spending' : 'Create New Spending';
@endphp
@section('admin_title', $title)
@section('content2')
    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a href="{{ url('admin/spendings') }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-6 col-sm-8">
                    <h3 class="mt-1 d-none d-md-bloack d-lg-block" style="font-family:cursive">
                        {{ $spending->id != null ? 'Edit Spending' : 'Create New Spending' }}</h3>

                    <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                        {{ $spending->id != null ? 'Edit Spending' : 'Create New Spending' }}</h5>
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
                action="{{ $spending->id != null ? route('update.spendings', ['id' => $spending->id]) : route('submit.spendings') }}"
                method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-12">
                        <label for="" class="fw-bold mb-2">Spend Title<span class="text-danger">*</span></label>
                        <input type="text" placeholder="Spend title" required
                            value="{{ old('title', $spending->title) }}"
                            class="form-control @error('title') is-invalid @enderror" name="title">
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                </div>


                <div class="row mt-3">

                     <div class="col-lg-4 col-md-6">
                        <label for="" class="fw-bold mb-2">Spend Amount<span class="text-danger">*</span></label>

                        <input type="number" min="1" required value="{{ old('date', $spending->amount) }}"
                            name="amount"
                            class="form-control @error('amount') is-invalid @enderror" placeholder="Spend Amount">

                        @error('amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>



                    <div class="col-lg-4 col-md-6">
                        <label for="" class="fw-bold mb-2">Spend Date<span class="text-danger">*</span></label>

                        <input type="date" required value="{{ old('date', $spending->date) }}"
                            name="date"
                            class="form-control @error('date') is-invalid @enderror">

                        @error('date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>






                <div class="row mt-3">
                    <div class="col-lg-8 col-md-12">
                        <label for="" class="fw-bold mb-2">Description (Optional)</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                            placeholder="Description" cols="30" rows="5">{{ old('description', $spending->description ?? '') }}</textarea>

                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>





                <button type="submit" name="action" value="save" class="btn btn-primary mt-3 float-end ms-2"
                    title="Save">
                    {{ $spending->id != null ? 'Update' : 'Save' }} <i class="bx bx-check-circle"></i>
                </button>

                @if ($spending->id == null)
                    <button type="submit" name="action" value="save_add_new" class="btn btn-dark mt-3 float-end"
                        title="Save and Add New">
                        Save & Add New <i class="bx bx-plus-circle"></i>
                    </button>
                @endif

            </form>
        </div>
    </div>

@endsection
