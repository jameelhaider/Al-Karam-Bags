@extends('dashboard.master2')
@section('content2')
    <style>
        .nav-tabs .nav-link.active {
            background-color: #f5f5f5 !important;
            color: #10559f !important;

        }

        .nav-tabs .nav-link {
            background-color: #f5f5f5 !important;
            color: rgb(117, 113, 113) !important;
            margin-bottom: 1px;
        }
    </style>
    <div class="container-fluid px-3">

        <ul class="nav nav-tabs" id="serviceTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ Request::is('admin/invoice/make') ? 'active' : '' }}"
                    href="{{ url('admin/invoice/make') }}">
                    Make Sale Invoice
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ Request::is('admin/invoices')  ? 'active' : '' }}"
                href="{{ url('admin/invoices') }}">
                    List Sale Invoices
                </a>
            </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link {{ Request::is('admin/invoices/return')  ? 'active' : '' }}"
                href="{{ url('admin/invoices/return') }}">
                    List Returned Invoices
                </a>
            </li>

        </ul>


    </div>
    <div class="tab-content">
        <div class="tab-pane fade show active">
            @yield('content4')
        </div>
    </div>

@endsection
