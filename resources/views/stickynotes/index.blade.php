@extends('dashboard.master2')
@section('admin_title', 'Admin | Sticky Notes')
@section('content2')

    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-3 col-6 col-md-4 col-sm-5">
                    <a href="{{ route('create.stickynote') }}"
                        class="btn btn-primary custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-plus me-1"></i> Add New Sticky Note
                    </a>
                </div>
                <div class="col-lg-9 col-6 col-md-8 col-sm-7">

                    <div class="d-flex align-items-center">
                        <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family: cursive;">Sticky Notes

                        </h3>
                        <small class="ms-2 text-muted d-none d-lg-block">Sticky Notes Hepls You To Remember Some Thing... etc: out of stock items , things to buy for shop. </small>
                        <h5 class="mt-1 d-block d-lg-none d-md-none d-sm-block" style="font-family: cursive;">Sticky Notes

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
                border:0px;
            }

            .custom-back-button i {
                font-size: 18px;
            }
        </style>




        <div class="card mb-2 p-2 mt-2">
            <form action="" method="GET">
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-sm-8 col-7 mt-1 mb-1">
                        <input type="text" class="form-control" value="{{ request()->title }}" placeholder="Title"
                            name="title">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-5 mt-1 mb-1">
                        <div class="btn-group w-100">
                            <a href="{{ url('admin/sticky-notes') }}" title="Clear"
                                class="btn btn-outline-danger">Clear</a>
                            <button type="submit" title="Search" class="btn btn-outline-success">Search</button>


                        </div>
                    </div>
                </div>
            </form>
        </div>


        @if ($stickynotes->count() > 0 && request()->title)
        <div class="alert bg-primary text-white mt-3">
            <strong>{{ $stickynotes->count() }}
                {{ $stickynotes->count() > 0 && $stickynotes->count() < 2 ? 'Result' : 'Results' }}
                Found</strong>
        </div>
    @elseif ($stickynotes->count() < 1 && request()->title != null)
        <div class="alert bg-warning text-white mt-3">
            <strong>No Results Found !</strong>
        </div>
    @endif


        <div class="card p-4 mb-0">
            @if ($stickynotes->count() > 0)
                <div class="row justify-content-around">
                    @foreach ($stickynotes as $stickynote)
                        <div class="col-lg-3 mb-3 col-md-4 col-sm-6">
                            <div class="sticky-note-card">
                                <!-- Pin Icon if Sticky Note is Pinned -->
                                @if ($stickynote->is_pinned)
                                    <div class="pin-icon">
                                        <i class="bx bx-pin" title="Pinned" aria-hidden="true"></i>
                                    </div>
                                @endif

                                <!-- Action Icons -->
                                <div class="action-icons">
                                    <a href="{{ route('stickynote.edit', ['id' => $stickynote->id]) }}"
                                        class="text-info me-1" title="Edit">
                                        <i class="bx bx-pencil" style="font-size: 17px"></i>
                                    </a>
                                    <a class="text-danger me-1"
                                        onclick="confirmDelete('{{ route('stickynote.delete', ['id' => $stickynote->id]) }}')"
                                        title="Delete">
                                        <i class="bx bx-trash" style="font-size: 17px"></i>
                                    </a>
                                    <!-- Pin/Unpin Action -->
                                    @if ($stickynote->is_pinned)
                                        <a class="text-warning" title="Unpin"
                                            onclick="confirmAction('{{ route('stickynote.unpin', ['id' => $stickynote->id]) }}', 'Unpin')">

                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"
                                                style="width: 17px; height: 17px;">
                                                <path
                                                    d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L481.4 352c9.8-.4 18.9-5.3 24.6-13.3c6-8.3 7.7-19.1 4.4-28.8l-1-3c-13.8-41.5-42.8-74.8-79.5-94.7L418.5 64 448 64c17.7 0 32-14.3 32-32s-14.3-32-32-32L192 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l29.5 0-6.1 79.5L38.8 5.1zM324.9 352L177.1 235.6c-20.9 18.9-37.2 43.3-46.5 71.3l-1 3c-3.3 9.8-1.6 20.5 4.4 28.8s15.7 13.3 26 13.3l164.9 0zM288 384l0 96c0 17.7 14.3 32 32 32s32-14.3 32-32l0-96-64 0z"
                                                    fill="#b19413" />
                                            </svg>

                                        </a>
                                    @else
                                        <a href="{{ route('stickynote.pin', ['id' => $stickynote->id]) }}"
                                            class="text-success" title="Pin">
                                            <i class="bx bx-pin" style="font-size: 17px"></i>
                                        </a>
                                    @endif
                                </div>

                                <h4 class="sticky-note-title">{{ $stickynote->title }}</h4>
                                <p class="sticky-note-content">{{ $stickynote->content }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <h3 class="h3 text-center fw-normal text-muted">No Data Found!</h3>
            @endif
        </div>




        <style>
            .sticky-note-card {
                background: linear-gradient(135deg, #99cffc,#dbd9d9);
                border-radius: 15px;
                padding: 25px;
                position: relative;
                min-height: 220px;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                overflow: hidden;
                transform: rotate(-2deg);
            }
            .sticky-note-card:hover {
                transform: translateY(-5px);
                background: linear-gradient(135deg, #dbd9d9,#99cffc);
                box-shadow: 0 12px 20px #2596be;
            }
            .sticky-note-title {
                font-size: 1.25rem;
                font-weight: 600;
                color: #4a4a4a;
                margin-top: 12px;
            }
            .sticky-note-content {
                font-size: 1rem;
                color: #606060;
                line-height: 1;
            }
            .pin-icon {
                position: absolute;
                top: 10px;
                left: 15px;
                color: #ff5c5c;
                font-size: 1.3rem;
                opacity: 0.8;
                transform: rotate(45deg);
            }
            .action-icons {
                position: absolute;
                top: 15px;
                right: 10px;
                display: flex;
                gap: 5px;
            }

            .action-icons a {
                font-size: 1.1rem;
                color: #888;
                transition: color 0.3s ease;
            }

            .action-icons a:hover {
                color: #555;
                cursor: pointer;
            }

            /* Optional paper-like border */
            .sticky-note-card:before {
                content: '';
                width: 100%;
                height: 15px;
                background: linear-gradient(90deg, #2596be70%, transparent 30%);
                position: absolute;
                top: -10px;
                left: 0;
                border-radius: 10px 10px 0 0;
            }
        </style>







    </div>




    <script>
        function confirmAction(url, action) {
            Swal.fire({
                title: `Are you sure you want to ${action} this Note?`,
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


        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure you want to delete this Note?',
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
