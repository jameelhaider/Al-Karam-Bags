@extends('dashboard.master2')
@php
    $title = $stickynote->id != null ? 'Edit Sticky Note' : 'Add New Sticky Note';
@endphp
@section('admin_title', $title)
@section('content2')
    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-6 col-sm-4">
                    <a href="{{ url('admin/sticky-notes') }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col0-md-9 col-6 col-sm-8">
                    <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">
                        {{ $stickynote->id != null ? 'Edit Sticky Note' : 'Add New Sticky Note' }}</h3>
                    <h5 class="mt-1 d-block d-md-none d-lg-none" style="font-family:cursive">
                        {{ $stickynote->id != null ? 'Edit Sticky Note' : 'Add New Sticky Note' }}</h5>
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
            action="{{ $stickynote->id != null ? route('update.stickynote', ['id' => $stickynote->id]) : route('submit.stickynote') }}"
            method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-6 col-md-8 col-12">
                    <label for="title" class="fw-bold mb-2">Note Title<span class="text-danger">*</span></label>
                    <input type="text" required placeholder="Note Title" value="{{ old('title', $stickynote->title) }}"
                        class="form-control @error('title') is-invalid @enderror" name="title" id="title">
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-lg-6 col-md-8 col-12">
                    <label for="content" class="fw-bold mb-2">Note Content<span class="text-danger">*</span></label>
                    <textarea name="content" required placeholder="Content" class="form-control @error('content') is-invalid @enderror"
                        cols="30" rows="10" id="content">{{ old('content', $stickynote->content) }}</textarea>
                    @error('content')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Is Pinned Checkbox -->
            <div class="row mt-2">
                <div class="col-lg-6 col-md-8 col-12">
                    <label for="is_pinned" class="fw-bold mb-2">Pin Note</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="is_pinned" id="is_pinned"
                            {{ old('is_pinned', $stickynote->is_pinned) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_pinned">Pin This Note</label>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-6 col-md-8 col-12">


                <button type="submit" name="action" value="save" class="btn btn-primary mt-3 float-end ms-2" title="Save">
                    {{ $stickynote->id != null ? 'Update' : 'Save' }} <i class="bx bx-check-circle"></i>
                </button>

                @if ($stickynote->id == null)
                    <button type="submit" name="action" value="save_add_new" class="btn btn-dark mt-3 float-end" title="Save and Add New">
                        Save & Add New <i class="bx bx-plus-circle"></i>
                    </button>
                @endif

                </div>
            </div>
        </form>

        </div>
    </div>
@endsection
