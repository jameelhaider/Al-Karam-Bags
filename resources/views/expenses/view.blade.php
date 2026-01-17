@extends('dashboard.master2')
@php
    $title = \Carbon\Carbon::parse($month->month)->format('F Y') . ' | View Expenses (' . $expenses->count() . ')';
@endphp

@section('admin_title', $title)


@section('content2')
    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-4 col-sm-4">
                    <a href="{{ url('admin/close-month') }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i>
                        Back
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-8 col-sm-8">
                    <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">
                        {{ \Carbon\Carbon::parse($month->month)->format('F Y') }} | View Expenses ({{ $expenses->count() }})
                    </h3>




                    <h6 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive"> {{ \Carbon\Carbon::parse($month->month)->format('F Y') }} | View Expenses ({{ $expenses->count() }})
                    </h6>
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



        <div class="row">
            <div class="col-lg-3 mt-3">
            </div>
            <div class="col-lg-6 mt-3">
                <div class="card p-2 mb-0" style="max-height: 500px;overflow:auto;">
                    @if ($expenses->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Name</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Amount</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $key => $expense)
                                        <tr class="text-center">
                                            <td>{{ ++$key }}</td>
                                            <td>
                                                @if ($expense->name)
                                                    {{ $expense->name }}
                                                @else
                                                    {{ $expense->expense_type }}
                                                @endif
                                            </td>
                                            <td>{{ 'Rs.' . number_format($expense->ammount) }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a style="color:rgb(222, 54, 54);cursor:pointer"
                                                        onclick="confirmDelete('{{ route('expense.delete', ['id' => $expense->id]) }}')"
                                                        title="Delete"><i class="bx bx-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="text-center fw-bold">
                                        <td colspan="2">Total</td>
                                        <td>{{ 'Rs.' . number_format($totalAmount) }}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Name</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Amount</th>
                                        <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <h3 class="h3 text-center fw-normal text-muted">No Data Found!</h3>
                    @endif

                </div>


            </div>
            <div class="col-lg-3"></div>
        </div>
    </div>



    <script>
        // Function to handle delete confirmation
        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure you want to delete this Expense?',
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
