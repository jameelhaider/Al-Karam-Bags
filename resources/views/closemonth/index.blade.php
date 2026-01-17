@extends('dashboard.master2')
@section('admin_title', 'Admin | Start Month / Expenses')
@section('content2')

    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-6 col-md-4 col-sm-5">
                    <a href="{{ route('create.closemonth') }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-plus me-1"></i> Start Month
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-8 col-sm-7">

                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-lg-block d-md-block" style="font-family: cursive;">Start Month / Expenses
                        </h3>
                        <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family: cursive;">Start Month / Expenses
                        </h5>
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
                border: 0px;
            }

            .custom-back-button i {
                font-size: 18px;
            }
        </style>


        <div class="card mb-2 p-2 mt-2">
            <form action="" method="GET">
                <div class="row">
                    <div class="col-lg-10 col-md-8 col-sm-6 col-6 mt-1 mb-1">
                        <input type="month" class="form-control" value="{{ request()->month }}" name="month">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-6 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ url('admin/close-month') }}" title="Clear" class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>

                        </div>
                    </div>
                </div>
            </form>
        </div>

        @if ($closemonths->count() > 0 && (request()->month))
        <div class="alert bg-primary text-white mt-3">
            <strong>{{ $closemonths->count() }} {{ $closemonths->count() > 0 && $closemonths->count() < 2 ? 'Result' : 'Results' }}
                Found</strong>
        </div>
    @elseif ($closemonths->count() < 1 && (request()->month!=null))
        <div class="alert bg-warning text-white mt-3">
            <strong>No Results Found !</strong>
        </div>
    @endif

        <div class="card p-2 mb-0">
            @if ($closemonths->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-fold">#</th>
                                <th style="font-size:14px" class="text-dark fw-fold">Month</th>
                                <th style="font-size:14px" class="text-dark fw-fold">No of Expenses</th>
                                <th style="font-size:14px" class="text-dark fw-fold">Total Expense</th>
                                <th style="font-size:14px" class="text-dark fw-fold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($closemonths as $key => $closemonth)
                                <tr class="text-center">
                                    <td class="text-dark">{{ ++$key }}</td>
                                    <td>
                                        <a class="text-dark fw-bold"
                                            href="{{ route('expense.view', ['month_id' => $closemonth->id]) }}">
                                            {{ \Carbon\Carbon::parse($closemonth->month)->format('F Y') }}</a>

                                    </td>
                                    <td>
                                        <a class="nav-link"
                                            href="{{ route('expense.view', ['month_id' => $closemonth->id]) }}"> <span
                                                class="badge bg-primary">{{ $closemonth->total_expenses }}</span></a>

                                    </td>

                                    <td>
                                        <a class="text-dark"
                                            href="{{ route('expense.view', ['month_id' => $closemonth->id]) }}">
                                            {{ 'Rs.' . number_format($closemonth->total_amount) }}</a>

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
                                                        href="{{ route('expense.view', ['month_id' => $closemonth->id]) }}">View
                                                        Expenses</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                        onclick="openAddExpenseModal({{ $closemonth->id }})">Add
                                                        Expense</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>



                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="float-end mt-2">
                        {{ $closemonths->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-fold">#</th>
                                <th style="font-size:14px" class="text-dark fw-fold">Month</th>
                                <th style="font-size:14px" class="text-dark fw-fold">No of Expenses</th>
                                <th style="font-size:14px" class="text-dark fw-fold">Total Expense</th>
                                <th style="font-size:14px" class="text-dark fw-fold">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <h4 class="h4 mt-2 text-center fw-normal text-muted">No Data Found!</h4>
            @endif
        </div>

        <!-- Add Expense Modal -->
        <div class="modal fade" id="markAsSoldModal" tabindex="-1" role="dialog" aria-labelledby="markAsSoldModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="markAsSoldModalLabel">Add Expense</h5>
                        <button type="button" class="btn btn-close" aria-label="Close" onclick="handleModalClose()">
                        </button>
                    </div>
                    <form id="markAsSoldForm" action="{{ route('expense.submit') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="closemonth_id" id="closemonth_id">
                            <div class="form-group">
                                <label>Expense Type <span class="text-danger">*</span></label>
                                <select name="expense_type" class="form-control" id="expense_type">
                                    <option value="">Select Type</option>
                                    <option value="Electricity Bill">Electricity Bill</option>
                                    <option value="Internet Bill">Internet Bill</option>
                                    <option value="Shop Rent">Shop Rent</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            <div class="form-group" id="expenseNameGroup" style="display:none;">
                                <label>Expense Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Expense Name" name="name"
                                    id="expenseName">
                            </div>
                            <div class="form-group">
                                <label for="soldOutDate">Expense Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="soldOutDate"
                                    placeholder="Expense Ammount" name="ammount" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal"
                                onclick="handleModalClose()">Close</button>
                            <button type="submit" class="btn btn-primary">Add Expense <i
                                    class="bx bx-check-circle"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openAddExpenseModal(closemonthId) {
                $('#closemonth_id').val(closemonthId);
                $('#markAsSoldModal').modal('show');
            }

            function handleModalClose() {
                $('#markAsSoldModal').modal('hide');
            }

            $(document).ready(function() {
                $('#expense_type').change(function() {
                    var expenseType = $(this).val();
                    if (expenseType === 'Others') {
                        $('#expenseNameGroup').show();
                        $('#expenseName').attr('required', true);
                    } else {
                        $('#expenseNameGroup').hide();
                        $('#expenseName').removeAttr('required');
                    }
                });
            });
        </script>
    </div>
@endsection
