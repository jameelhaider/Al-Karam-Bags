
@php
   $layout = isset($type->id) ? 'dashboard.master2' : 'components.stockstabs';
    $title=$type->id?'Admin | Edit Type':'Admin | Add New Type';
@endphp
@extends($layout)
@section('admin_title', $title)
@section(isset($type->id) ? 'content2' : 'content3')


    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a
                    @if (request()->type=='tools')
                    href="{{ url('admin/stocks/tools/types') }}"
                    @elseif (request()->type=='parts')
                    href="{{ url('admin/stocks/parts/types') }}"
                                        @endif
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col0-md-9 col-6 col-sm-8">
                    <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">
                        {{ $type->id != null ? 'Edit Type' : 'Add New Type' }}</h3>
                        <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                            {{ $type->id != null ? 'Edit Type' : 'Add New Type' }}</h5>
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
                action="{{ $type->id != null ? route('update.type', ['id' => $type->id]) : route('submit.type') }}"
                method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ request()->type }}">
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <label for="" class="fw-bold mb-2">Type Name<span class="text-danger">*</span></label>
                        <input type="text" required placeholder="Type Name"
                               value="{{ old('name', $type->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                </div>









                <button type="submit" name="action" value="save" class="btn btn-primary mt-3 float-end ms-2" title="Save">
                    {{ $type->id != null ? 'Update' : 'Save' }} <i class="bx bx-check-circle"></i>
                </button>

                @if ($type->id == null)
                    <button type="submit" name="action" value="save_add_new" class="btn btn-dark mt-3 float-end" title="Save and Add New">
                        Save & Add New <i class="bx bx-plus-circle"></i>
                    </button>
                @endif

            </form>
        </div>
    </div>
@endsection
