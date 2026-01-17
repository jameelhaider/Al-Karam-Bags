@extends('dashboard.master2')
@php
    $title = $cash->id != null ? 'Edit Receiving' : 'Add New Receiving';
@endphp
@section('admin_title', $title)
@section('content2')
    <div class="container-fluid px-3">

        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a href="{{ url('admin/cash-received') }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-md-9 col-6 col-sm-8">
                    <h3 class="mt-1 d-none d-md-bloack d-lg-block" style="font-family:cursive">
                        {{ $cash->id != null ? 'Edit Receiving' : 'Add New Receiving' }}</h3>

                    <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                        {{ $cash->id != null ? 'Edit Receiving' : 'Add New Receiving' }}</h5>
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
            <form action="{{ $cash->id != null ? route('update.cash', ['id' => $cash->id]) : route('submit.cash') }}"
                method="POST">
                @csrf
                <div class="row">

                    {{-- ACCOUNT SELECT --}}
                    @if (!$cash->id)
                        <div class="col-lg-6 col-md-6">
                        @else
                            <div class="col-lg-12 col-md-12">
                    @endif


                    <label class="fw-bold mb-2">Select Customer<span class="text-danger">*</span></label>
                    <select name="account_id" required class="form-select" id="acc-select">
                        <option value="">Select Account</option>
                        @foreach ($accounts as $account)
                            @if ($account->id != 1)
                                <option value="{{ $account->id }}" data-balance="{{ $account->prev_balance }}"
                                    {{ $cash->account_id == $account->id ? 'selected' : '' }}>
                                    {{ $account->id . ') ' . $account->customer_name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- CURRENT BALANCE --}}
                @if (!$cash->id)
                    <div class="col-lg-6 col-md-6">
                        <label class="fw-bold mb-2">Current Balance</label>
                        <input type="text" id="current_balance" class="form-control" readonly>
                    </div>
                @endif


                {{-- NIL BALANCE CHECKBOX --}}
                <div class="col-lg-12 mt-2" id="nil_box" style="display:none;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="nil_balance_chk">
                        <label class="form-check-label fw-bold" for="nil_balance_chk">
                            Receive Full (Check To Nill Due Balance)
                        </label>
                    </div>
                </div>


                <div class="col-lg-6 col-md-6 mt-3">
                    <label class="fw-bold mb-2">Select Narration<span class="text-danger">*</span></label>
                    <select name="narration" required class="form-select @error('narration') is-invalid @enderror"
                        id="narration-select">

                        <option value="Cash" {{ $cash->narration == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Raast ID" {{ $cash->narration == 'Raast ID' ? 'selected' : '' }}>Raast ID</option>
                        <option value="Easy Paisa" {{ $cash->narration == 'Easy Paisa' ? 'selected' : '' }}>Easy Paisa
                        </option>
                        <option value="Jazz Cash" {{ $cash->narration == 'Jazz Cash' ? 'selected' : '' }}>Jazz Cash
                        </option>
                        <option value="Raast ID + Cash" {{ $cash->narration == 'Raast ID + Cash' ? 'selected' : '' }}>
                            Raast ID + Cash</option>
                        <option value="Easy Paisa + Cash" {{ $cash->narration == 'Easy Paisa + Cash' ? 'selected' : '' }}>
                            Easy Paisa + Cash</option>
                        <option value="Jazz Cash + Cash" {{ $cash->narration == 'Jazz Cash + Cash' ? 'selected' : '' }}>
                            Jazz Cash + Cash</option>

                    </select>
                </div>




                {{-- AMOUNT --}}
                <div class="col-lg-6 col-md-6 mt-3">
                    <label class="fw-bold mb-2">Received Amount<span class="text-danger">*</span></label>
                    <input type="number" min="1" required placeholder="Received Amount"
                        value="{{ $cash->ammount }}" class="form-control @error('ammount') is-invalid @enderror"
                        name="ammount" id="amount_input">
                </div>

        </div>

        {{-- BUTTONS --}}
        <button type="submit" name="action" value="save" class="btn btn-primary mt-3 float-end ms-2"
            title="Save : Add Receiving And Redirect To Back">
            {{ $cash->id != null ? 'Update Receiving' : 'Save Receiving' }} <i class="bx bx-check-circle"></i>
        </button>

        @if ($cash->id == null)
            <button type="submit" name="action" value="save_add_new" class="btn btn-dark mt-3 float-end"
                title="Save and Add New : Add Receiving And Stay On This Page">
                Save & Add New <i class="bx bx-plus-circle"></i>
            </button>
        @endif

        </form>






        @if (!$cash->id)
            <h4 class="fw-bold text-center mt-3 mb-3 text-dark">Last 20 Cash Receiveds</h4>
            @if ($cash_receiveds->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="font-size:14px" class="text-dark fw-bold text-center">#</th>
                                <th style="font-size:14px;width:9%" class="text-dark fw-bold text-center">Acc ID</th>
                                <th style="font-size:14px;width:28%" class="text-dark fw-bold">Customer Name</th>
                                <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Narration</th>
                                <th style="font-size:14px;width:20%" class="text-dark fw-bold text-center">Amount Received
                                </th>
                                <th style="font-size:14px" class="text-dark fw-bold text-center">R.A.R</th>
                                <th style="font-size:14px;width:15%" class="text-dark fw-bold text-center">Date</th>
                                <th style="font-size:14px" class="text-dark fw-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cash_receiveds as $key => $csh)
                                <tr>
                                    <td class="text-dark text-center">{{ ++$key }}</td>
                                    <td class="text-dark text-center">{{ $csh->account_id }}</td>
                                    <td>
                                        <a class="text-dark fw-bold" target="_BLANK"
                                            href="{{ route('cash.view.receipt', ['id' => $csh->id]) }}">
                                            {{ $csh->customer_name }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a class="text-dark" target="_BLANK"
                                            href="{{ route('cash.edit', ['id' => $csh->id]) }}">
                                            {{ $csh->narration }}
                                        </a>
                                    </td>
                                    <td class="text-dark text-center fw-bold" style="font-size: 18px;">
                                        {{ 'Rs.' . number_format($csh->ammount) }}
                                    </td>
                                    <td class="text-dark text-center">
                                        @if ($csh->final_rem > 0)
                                            <span class="fw-bolder" style="color: rgb(253, 27, 27);">
                                                {{ 'Rs.' . number_format($csh->final_rem) }}</span>
                                        @elseif ($csh->final_rem < 0)
                                            <span class="fw-bolder" style="color: #6c757d;">
                                                {{ 'Rs.' . number_format($csh->final_rem) }}</span>
                                        @else
                                            <span class="fw-bolder" style="color: rgb(1, 149, 1);">
                                                {{ 'Rs.' . number_format($csh->final_rem) }}</span>
                                        @endif

                                    </td>

                                    <td class="text-dark text-center">
                                        {{ \Carbon\Carbon::parse($csh->created_at)->format('d M y') }}
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
                                                    <a class="dropdown-item" target="_BLANK"
                                                        href="{{ route('cash.view.receipt', ['id' => $csh->id]) }}">View
                                                        Receipt</a>
                                                </li>
                                                @php
                                                    $canEditOrDelete = now()->diffInHours($csh->created_at) < 24;
                                                @endphp

                                                @if ($canEditOrDelete)
                                                    <li>
                                                        <a class="dropdown-item" title="Edit"
                                                            href="{{ route('cash.edit', ['id' => $csh->id]) }}">Edit</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" title="Delete"
                                                            onclick="confirmDelete('{{ route('cash.delete', ['id' => $csh->id]) }}')">Delete</a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a class="dropdown-item text-muted disabled" href="#">Edit
                                                            (Disabled 24h Passed)
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-muted disabled" href="#">Delete
                                                            (Disabled 24h Passed)</a>
                                                    </li>
                                                @endif

                                            </ul>
                                        </div>
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th style="font-size:14px" class="text-dark fw-bold">#</th>
                                <th style="font-size:14px;width:9%" class="text-dark fw-bold">Acc ID</th>
                                <th style="font-size:14px;width:26%" class="text-dark fw-bold">Customer Name</th>
                                <th style="font-size:14px;width:13%" class="text-dark fw-bold">Narration</th>
                                <th style="font-size:14px;width:20%" class="text-dark fw-bold">Amount Received</th>
                                <th style="font-size:14px" class="text-dark fw-bold">R.A.R</th>
                                <th style="font-size:14px;width:15%" class="text-dark fw-bold">Date</th>
                                <th style="font-size:14px" class="text-dark fw-bold">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <h4 class="h4 text-center fw-normal text-muted mt-2">No Data Found!</h4>
            @endif
        @endif





    </div>
    </div>


        <script>
        // Function to handle delete confirmation
        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure you want to delete this Entry?',
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
    
    <style>
        .select2-container--default .select2-selection--single {
            display: block;
            width: 100%;
            padding: 0.300rem 0.200rem 0.300rem 0.200rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            height: auto;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50%;
            right: 0.32rem;
            transform: translateY(-50%);
            height: auto;
        }
    </style>

    {{-- JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>

    <script>
        $('#acc-select').select2({
            placeholder: 'Select Account',
            allowClear: true
        });
        $('#narration-select').select2({
            placeholder: 'Select Narration',
        });

        function updateBalanceUI() {
            let balance = $('#acc-select option:selected').data('balance') ?? 0;
            $('#current_balance').val(balance);

            if (balance > 0) {
                $('#nil_box').show();
            } else {
                $('#nil_box').hide();
                $('#nil_balance_chk').prop('checked', false);
                $('#amount_input').prop('readonly', false);
            }
        }

        $('#acc-select').on('change', function() {
            updateBalanceUI();
        });

        $('#nil_balance_chk').on('change', function() {
            let balance = $('#acc-select option:selected').data('balance') ?? 0;
            if ($(this).is(':checked')) {
                $('#amount_input').val(balance).prop('readonly', true);
            } else {
                $('#amount_input').prop('readonly', false).val('');
            }
        });

        $(document).ready(function() {
            $('#acc-select').trigger('change');
        });
    </script>

@endsection
