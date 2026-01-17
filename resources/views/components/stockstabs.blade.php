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
    <div class="container-fluid">

        <ul class="nav nav-tabs" id="serviceTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ Request::is('admin/stocks/tools') || Request::is('admin/stocks/parts') || Request::is('admin/services/3') || Request::is('admin/services/4') ? 'active' : '' }}"
                    @if (request()->type == 'tools') href="{{ url('admin/stocks/tools') }}"
                @else href="{{ url('admin/stocks/parts') }}" @endif>
                    List All Stock
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ Request::is('admin/stocks/parts/create') || Request::is('admin/stocks/tools/create')  ? 'active' : '' }}"
                    @if (request()->type == 'parts') href="{{ url('admin/stocks/parts/create') }}"
                @else href="{{ url('admin/stocks/tools/create') }}" @endif>
                    Add New Stock
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ Request::is('admin/stocks/tools/companies') || Request::is('admin/stocks/parts/companies') ? 'active' : '' }}"
                    @if (request()->type == 'tools') href="{{ url('admin/stocks/tools/companies') }}"
                @else href="{{ url('admin/stocks/parts/companies') }}" @endif>
                    List All Companies
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link {{ Request::is('admin/stocks/tools/companies/create') || Request::is('admin/stocks/parts/companies/create') ? 'active' : '' }}"
                    @if (request()->type == 'tools') href="{{ url('admin/stocks/tools/companies/create') }}"
                @else href="{{ url('admin/stocks/parts/companies/create') }}" @endif>
                Add New Company
                </a>
            </li>



            <li class="nav-item" role="presentation">
                <a class="nav-link {{ Request::is('admin/stocks/tools/models') || Request::is('admin/stocks/parts/models') ? 'active' : '' }}"
                    @if (request()->type == 'tools') href="{{ url('admin/stocks/tools/models') }}"
                @else href="{{ url('admin/stocks/parts/models') }}" @endif>
                    List All Models
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link {{ Request::is('admin/stocks/tools/models/create') || Request::is('admin/stocks/parts/models/create') ? 'active' : '' }}"
                    @if (request()->type == 'tools') href="{{ url('admin/stocks/tools/models/create') }}"
                @else href="{{ url('admin/stocks/parts/models/create') }}" @endif>
                Add New Model
                </a>
            </li>


          {{-- @if (request()->type=='parts') --}}
          <li class="nav-item" role="presentation">
            <a class="nav-link {{ Request::is('admin/stocks/tools/types') || Request::is('admin/stocks/parts/types') ? 'active' : '' }}"
                @if (request()->type == 'tools') href="{{ url('admin/stocks/tools/types') }}"
            @else href="{{ url('admin/stocks/parts/types') }}" @endif>
                List All Types
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link {{ Request::is('admin/stocks/tools/types/create') || Request::is('admin/stocks/parts/types/create') ? 'active' : '' }}"
                @if (request()->type == 'tools') href="{{ url('admin/stocks/tools/types/create') }}"
            @else href="{{ url('admin/stocks/parts/types/create') }}" @endif>
            Add New Type
            </a>
        </li>
          {{-- @endif --}}




        </ul>


    </div>


    <div class="tab-content">
        <div class="tab-pane fade show active">
            @yield('content3')
        </div>
    </div>

@endsection
