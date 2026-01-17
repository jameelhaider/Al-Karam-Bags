@extends('components.stockstabs')
@section('admin_title', 'Admin | Types')
@section('content3')

    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-6 col-md-4 col-sm-5">
                    <a href="{{ route('create.type',['type'=>request()->type]) }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-plus me-1"></i> Add New Type
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-8 col-sm-7">

                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-lg-block d-md-block" style="font-family: cursive;">Types</h3>
                        <h5 class="mt-1 d-block d-lg-none d-md-none" style="font-family: cursive;">Types</h5>

                    </div>


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
                background-color: #2596be;
                border:0px;
            }

            .custom-back-button i {
                font-size: 18px;
            }
        </style>


        <div class="card mb-2 p-2 mt-2" >
            <form action="" method="GET">
                <div class="row">
                    <div class="col-lg-9 col-md-4 col-sm-4 col-6 mt-1 mb-1">
                        <input type="text" class="form-control" value="{{ request()->name }}" placeholder="Type Name"
                            name="name">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4 col-12 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a
                            href="{{ url('admin/stocks/parts/types') }}"
                            title="Clear" class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        @if ($types->count() > 0 && (request()->name))
            <div class="alert bg-primary text-white mt-3">
                <strong>{{ $types->count() }} {{ $types->count() > 0 && $types->count() < 2 ? 'Result' : 'Results' }}
                    Found</strong>
            </div>
        @elseif ($types->count() < 1 && (request()->name!=null))
            <div class="alert bg-warning text-white mt-3">
                <strong>No Results Found !</strong>
            </div>
        @endif



        <div class="card p-2 mb-0">
            @if ($types->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Type Name</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($types as $key => $type)
                                <tr class="text-center">
                                    <td class="text-dark">{{ ++$key }}</td>
                                    <td>
                                        <a class="text-dark fw-bold" href="{{ route('type.edit', ['id' => $type->id,'type'=>request()->type]) }}">
                                            {{ $type->name }}
                                        </a>
                                    </td>


                                    <td>
                                        <div class="dropdown ms-auto">
                                            <button class="btn btn-dark btn-sm dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('type.edit', ['id' => $type->id,'type'=>request()->type]) }}">Edit</a>
                                                </li>
                                                    {{-- <li>
                                            <a class="dropdown-item"
                                                onclick="confirmDelete('{{ route('type.delete', ['id' => $type->id]) }}')">Delete</a>
                                        </li> --}}
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="float-end mt-2">
                        {{ $types->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Type Name</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <h4 class="h4 text-center fw-normal text-muted mt-2">No Data Found!</h4>
            @endif
        </div>

    </div>








    <script>
        // Function to handle confirmation for activating or deactivating slides
        function confirmAction(url, action) {
            Swal.fire({
                title: `Are you sure you want to ${action} this Type?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Yes, ${action} it!`,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }

        // Function to handle delete confirmation
        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure you want to delete this Type?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }
    </script>
@endsection
