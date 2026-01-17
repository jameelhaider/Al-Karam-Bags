@extends('dashboard.master2')
@section('admin_title', 'Admin | Copy Raw Parts')

@section('content2')
<div class="container-fluid px-3">

    <h3 class="text-center fw-bold text-dark mb-3">
        Copy Parts Raw Data <i class="bx bx-copy"></i>
    </h3>

    {{-- Form --}}
    <div class="card p-3 shadow rounded-0">

        <form method="GET" action="{{ url('admin/copy-row-parts') }}">

            {{-- Company --}}
            <label class="fw-bold">Select Company</label>
            <select name="company_id" id="company-select" class="form-select" required>
                <option value="">Select Company</option>
                @foreach ($partscompanies as $company)
                    <option value="{{ $company->id }}"
                        {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>

            {{-- Type --}}
            <label class="fw-bold mt-3">Select Type</label>
            <select name="type_id" id="type-select" class="form-select" required>
                <option value="">Select Type</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}"
                        {{ request('type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>

            {{-- Buttons --}}
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-primary rounded-0 w-100">
                    Generate Raw Data
                </button>

                <a href="{{ url('admin/copy-row-parts') }}"
                   class="btn btn-dark rounded-0 w-100">
                    Clear & Reset
                </a>
            </div>

        </form>
    </div>

    {{-- Output --}}
    @if ($output)
        <div class="card mt-4 p-3 shadow rounded-0">

            <h5 class="fw-bold mb-2">
                Raw Parts Price List
            </h5>

            <textarea
                id="rawOutput"
                class="form-control"
                rows="15"
                readonly
                style="font-family: monospace;"
            >{{ $output }}</textarea>

            <button
                type="button"
                class="btn btn-primary rounded-0 mt-3 w-100"
                onclick="copyRawData()">
                <i class="bx bx-copy"></i> Copy to Clipboard
            </button>

            <a href="{{ url('admin/copy-row-parts') }}"
               class="btn btn-dark rounded-0 mt-2 w-100">
                Clear & Reset
            </a>

        </div>
    @endif

</div>
<script>
    function copyRawData() {
        const textarea = document.getElementById('rawOutput');
        textarea.select();
        textarea.setSelectionRange(0, 99999);
        document.execCommand('copy');
        alert('Raw data copied successfully!');
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script>
        $('#company-select').select2({
            placeholder: 'Select Company',
            allowClear: true
        });
        $('#type-select').select2({
            placeholder: 'Select Type',
            allowClear: true
        });
    </script>
@endsection
