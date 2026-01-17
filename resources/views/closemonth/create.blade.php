@extends('dashboard.master2')
@section('admin_title', 'Admin | Start Month')
@section('content2')
    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a href="{{ url('admin/close-month') }}" class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-6 col-md-9 col-sm-8">
                  <h3 class="mt-1 d-none d-lg-block d-md-block" style="font-family:cursive">{{ $closemonth->id != null ? 'Edit Month' : 'Start Month' }}</h3>
                  <h5 class="mt-1 d-block d-lg-none d-md-none" style="font-family:cursive">{{ $closemonth->id != null ? 'Edit Month' : 'Start Month' }}</h5>
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
                action="{{ $closemonth->id != null ? route('update.closemonth', ['id' => $closemonth->id]) : route('submit.closemonth') }}"
                method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <label for="" class="fw-bold mb-2">Month <span class="text-danger">*</span></label>
                        <input type="month" value="{{ $closemonth->month }}" class="form-control @error('month') is-invalid @enderror" name="month" id="monthInput">
                        @error('month')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const monthInput = document.getElementById('monthInput');
                            const currentDate = new Date();
                            const currentYear = currentDate.getFullYear();
                            const currentMonth = String(currentDate.getMonth() + 1).padStart(2, '0');
                            const maxMonth = `${currentYear}-${currentMonth}`;

                            monthInput.setAttribute('max', maxMonth);
                        });
                    </script>


                </div>

                <button type="submit" class="btn btn-primary mt-3 float-end" title="Submit">
                    {{ $closemonth->id != null ? 'Update' : 'Start' }}
                    <i class="bx bx-check-circle"></i>
                </button>


            </form>
        </div>
    </div>
@endsection
