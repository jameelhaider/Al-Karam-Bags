@extends('dashboard.master2')
@php
    $title = 'View | ' . $stock->name;
@endphp
@section('admin_title', $title)
@section('content2')
    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a href="{{ url('admin/demands') }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-sm-8 col-9">
                    <h5 class="mt-1 d-none d-lg-none d-md-block" style="font-family:cursive">View | {{ $stock->name }}
                    </h5>
                    <h3 class="mt-1 d-none d-lg-block d-md-none" style="font-family:cursive">View | {{ $stock->name }}
                    </h3>

                    <small class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">View | {{ $stock->name }}
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

    </div>
@endsection
