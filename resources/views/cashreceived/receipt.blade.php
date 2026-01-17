@extends('dashboard.master2')
@php
    $title = 'View Receipt | ' . $cash->customer_name;
@endphp
@section('admin_title', $title)
@section('content2')

    <div class="container-fluid px-3">
        <div class="card shadow-sm bg-white rounded-0">
            <div class="row">
                <div class="col-lg-2 col-3 col-sm-2">
                    <a href="{{ route('index.cash') }}"
                        class="btn btn-dark custom-back-button d-flex align-items-center justify-content-center">
                        <i class="bx bx-chevron-left me-1"></i> Back
                    </a>
                </div>
                <div class="col-lg-10 col-9 col-sm-10">
                    <h3 class="mt-1 d-none d-md-block d-lg-block" style="font-family:cursive">View Receipt |
                        {{ $cash->customer_name }} | {{ \Carbon\Carbon::parse($cash->created_at)->format('F d, Y') }}
                    </h3>
                    <small class="mt-1 d-block d-md-none" style="font-family:cursive">View Receipt |
                        {{ $cash->customer_name }} | {{ \Carbon\Carbon::parse($cash->created_at)->format('F d, Y') }}
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





        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12 col-xl-6 mt-2">

                <div class="card p-2 mt-2 mb-2" style="border-radius: 0%">
                    <div class="d-flex justify-content-between gap-2">
                        <button id="downloadImage" class="btn btn-sm btn-dark w-50">
                            Download as Image <i class="bx bx-image"></i>
                        </button>
                        <button id="downloadPDF" class="btn btn-sm btn-primary w-50">
                            Download as PDF <i class="bx bxs-file-pdf"></i>
                        </button>
                    </div>
                </div>




                <div class="card p-2 shadow" style="border-radius: 25px" id="receipt-card">
                    <div class="d-flex">
                        <div style="flex: 0 0 100px;">
                            <img src="{{ asset('uploads/c2.png') }}" class="img-fluid" alt="">
                        </div>
                        <div class="ms-2 flex-grow-1">
                            <h3 class="text-dark fw-bold mb-2">Al-Karam Bags</h3>
                            <div class="row">
                                <div class="col-6">
                                    <small class="fw-bold text-dark">Address:</small>
                                    <p class="mb-1 text-dark" style="font-size: 12px;">Shop # FF/340 D-Point Plaza GT
                                        Road, Gujranwala</p>
                                </div>
                                <div class="col-6">
                                    <small class="fw-bold text-dark">Contact:</small>
                                    <p class="mb-1 text-dark" style="font-size: 12px;">
                                        <strong>Husnain Nasir:</strong> 0337-4967077<br>
                                        <strong>Nasir Ahmad:</strong> 0305-5760932
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2 mb-1" style="border-top: 1px solid rgba(0, 0, 0, 0.525)">

                    <h3 class="text-dark text-center mt-1"><u>Cash Received Receipt <i style="font-size: 27px"
                            class="bx bx-receipt"></i></u></h3>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center" style="font-size: 5px">
                                    <th class="text-dark fw-bold" style="font-size: 10px;">Acc ID</th>
                                    <th class="text-dark fw-bold" style="font-size: 10px">Narration</th>
                                    <th class="text-dark fw-bold" style="font-size: 10px">Amount</th>
                                    <th class="text-dark fw-bold" style="font-size: 10px">Date</th>
                                    <th class="text-dark fw-bold" style="font-size: 10px">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center text-dark">
                                    {{-- <th class="text-dark">1</th> --}}
                                    <th class="text-dark">{{ $cash->account_id }}</th>
                                    <td>{{ $cash->narration }}</td>
                                    <td>{{ 'Rs.' . number_format($cash->ammount) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cash->created_at)->format('F d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cash->created_at)->format('h:i A') }}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="text-dark fw-bold mb-0">{{ $cash->customer_name }}</h3>
                            <img src="{{ asset('uploads/cash.png') }}" style="height: 100px; width: 100px;"
                                alt="Cash Image">
                        </div>
                        <p class="text-dark mt-3">
                            Amount of Rs.{{ number_format($cash->ammount) }} has been received on
                            {{ \Carbon\Carbon::parse($cash->created_at)->format('F d, Y') }}
                            through {{ $cash->narration }}.
                        </p>

                        <strong class="text-dark">Note:</strong>
                        <p class="text-dark">Remaining Balance After Receiving Rs.{{ number_format($cash->ammount) }} is
                            Rs.{{ number_format($cash->final_rem) }}</p>





                        {{-- <hr> --}}
                        <hr style="border-top: 1px solid rgba(0, 0, 0, 0.525)">
                        <h5 class="text-dark fw-bold text-center">Thanks For Bussiness With Us...</h5>
                        <hr style="border-top: 1px solid rgba(0, 0, 0, 0.525)">
                        {{-- <hr> --}}


                    </div>






                </div>
            </div>
        </div>













        <script>
            // Download as Image
            document.getElementById("downloadImage").addEventListener("click", function() {
                const receipt = document.getElementById("receipt-card");
                html2canvas(receipt).then(canvas => {
                    const link = document.createElement('a');
                    link.download = 'receipt.png';
                    link.href = canvas.toDataURL("image/png");
                    link.click();
                });
            });

            // Download as PDF
            document.getElementById("downloadPDF").addEventListener("click", function() {
                const {
                    jsPDF
                } = window.jspdf;
                const receipt = document.getElementById("receipt-card");

                html2canvas(receipt, {
                    scale: 2
                }).then(canvas => {
                    const imgData = canvas.toDataURL("image/png");
                    const pdf = new jsPDF("p", "mm", "a5");

                    const imgProps = pdf.getImageProperties(imgData);
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    pdf.save("receipt.pdf");
                });
            });
        </script>





    </div>


@endsection
