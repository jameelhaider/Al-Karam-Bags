@extends('dashboard.master2')
@section('admin_title', 'Al-Karam Bags | Dashboard')
@section('content2')
    <style>
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            margin-top: 20px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.205);
        }

        .card:hover {
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
        }

        .card-header {
            background: linear-gradient(135deg, #10559f 70%, #bac2c8 70%);
            padding: 10px 10px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .card-header:hover {
            background: linear-gradient(135deg, #10559f, #2596be, #bac2c8);
        }

        .card-body {
            font-size: 1.3rem;
            padding: 20px;
            font-weight: 900;
            color: #000000;
            background: #d6e3f3;
        }

        .card-icon {
            font-size: 1.5rem;
            margin-right: 10px;
        }
    </style>

    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
            background: linear-gradient(135deg, rgba(106, 102, 102, 0.16), white)
        }

        .chart-container canvas {
            height: 100% !important;
            width: 100% !important;
        }
    </style>

    <div class="container-fluid px-3">















        <h2 class="fw-bold text-center text-dark">Total Rass</h2>

        <div class="row justify-content-around">
            <div class="col-lg-12 col-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <i class="card-icon bx bx-wallet"></i> Total Rass
                        </div>

                        <!-- Toggle Switch -->
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="toggleRass">
                            <label class="form-check-label" for="toggleRass">Show Rass</label>
                        </div>
                    </div>

                    <div class="card-body text-center count-animation text-primary" id="rassValue">
                        RS.****
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Modal -->
        <div class="modal fade" id="passwordModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="password" autocomplete="new-password" id="confirmPassword" class="form-control"
                            placeholder="Enter password">
                        <small id="errorMsg" class="text-danger mt-2 d-none">Incorrect password!</small>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" id="submitPassword">Confirm</button>
                    </div>

                </div>
            </div>
        </div>


        <script>
    let realValue = "{{ $totalrass }}";

    function formatNumber(num) {
        return new Intl.NumberFormat().format(num);
    }

    document.getElementById("toggleRass").addEventListener("change", function () {
        if (this.checked) {
            let modal = new bootstrap.Modal(document.getElementById('passwordModal'));
            modal.show();
        } else {
            document.getElementById("rassValue").innerText = "RS.****";
        }
    });

    document.getElementById("submitPassword").addEventListener("click", function() {
        let pass = document.getElementById("confirmPassword").value;

        if (pass === "2cbc0") {
            document.getElementById("rassValue").innerText = "RS." + formatNumber(realValue);
            document.getElementById("errorMsg").classList.add("d-none");
            bootstrap.Modal.getInstance(document.getElementById('passwordModal')).hide();
        } else {
            document.getElementById("errorMsg").classList.remove("d-none");
            document.getElementById("toggleRass").checked = false;
            document.getElementById("rassValue").innerText = "RS.****";
        }
    });
</script>





        <h2 class="fw-bold text-center text-dark">Total Cash Remainings To Receive</h2>
        <div class="row justify-content-around">
            <div class="col-lg-12 col-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        Cash To Receive
                    </div>

                    <div class="card-body text-center count-animation text-primary" data-count="{{ $totalrem }}">
                        RS.0
                    </div>
                </div>
            </div>

        </div>





        <h2 class="fw-bold text-center text-dark">This Month Stats</h2>
        <div class="row justify-content-around">


            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        This Month Revenue
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $thisMonthRevenue }}">
                        RS.0
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        This Month Expenses
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $totalExpensesthismonth }}">
                        RS.0
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        This Month Profit
                    </div>
                    <div class="card-body text-center count-animation"
                        data-count="{{ $totalProfitthismonthAfterExpenses }}">
                        RS.0
                    </div>
                </div>
            </div>





        </div>













        <h2 class="fw-bold text-center text-dark">Sales</h2>
        <div class="row justify-content-around">
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        Today Sales
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $totaltodaysales }}">
                        RS.0
                    </div>
                </div>
            </div>






            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        This Week Sales
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $totalthisweeksales }}">
                        RS.0
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        This Month Sales
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $totalthismonthsales }}">
                        RS.0
                    </div>
                </div>
            </div>




            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        Previous Month Sales
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $totalprevmonthsales }}">
                        RS.0
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        This Year Sales
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $totalthisyearsales }}">
                        RS.0
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        OverAll Sales
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $totaloverallsales }}">
                        RS.0
                    </div>
                </div>
            </div>

        </div>






        <h2 class="fw-bold text-center text-dark">Revenues</h2>
        <div class="row justify-content-around">
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        Today Revenue
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $todayRevenue }}">
                        RS.0
                    </div>
                </div>
            </div>






            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        This Week Revenue
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $thisWeekRevenue }}">
                        RS.0
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        This Month Revenue
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $thisMonthRevenue }}">
                        RS.0
                    </div>
                </div>
            </div>




            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        Previous Month Revenue
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $previousMonthRevenue }}">
                        RS.0
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        This Year Revenue
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $thisYearRevenue }}">
                        RS.0
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-money"></i>
                        OverAll Revenue
                    </div>
                    <div class="card-body text-center count-animation" data-count="{{ $overallRevenue }}">
                        RS.0
                    </div>
                </div>
            </div>

        </div>

        <div class="row justify-content-around">

            <h2 class="fw-bold text-center text-dark">Stocks Count</h2>



            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-package"></i>
                        Total Stock Items
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totalstockitems }}">
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-package"></i>
                        Total Parts Items
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totalparts }}">
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-package"></i>
                        Total Tools Items
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totaltools }}">
                    </div>
                </div>
            </div>



            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-check-circle"></i>
                        Total Available Stock Items
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totalavailitems }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-x"></i>
                        Total Out Off Stock Items
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totaloutitems }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-error"></i>
                        Total Low Stock Items
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totallowitems }}">
                    </div>
                </div>
            </div>



            {{-- //parts --}}
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-check-circle"></i>
                        Total Available Parts Items
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totalavailitemsparts }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-x"></i>
                        Total Out Off Stock Parts
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totaloutitemsparts }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-error"></i>
                        Total Low Stock Parts Items
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totallowitemsparts }}">
                    </div>
                </div>
            </div>



            {{-- tools --}}
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-check-circle"></i>
                        Total Available Tools Items
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totalavailitemstools }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-x"></i>
                        Total Out Off Stock Tools
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totaloutitemstools }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-error"></i>
                        Total Low Stock Tools Items
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totallowitemstools }}">
                    </div>
                </div>
            </div>



            <h2 class="fw-bold text-center text-dark">Demands Count</h2>

            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-list-ul"></i>
                        Total Damands
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totaldemands }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-list-ul"></i>
                        Total Parts Damands
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totalpartsdemands }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-list-ul"></i>
                        Total Tools Damands
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totaltoolsdemands }}">
                    </div>
                </div>
            </div>



            <h2 class="fw-bold text-center text-dark">Others Count</h2>




            <div class="col-lg-4 col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="card-icon bx bx-group"></i>
                        Total Accounts
                    </div>
                    <div class="card-body text-center count-animation-2" data-count="{{ $totalaccounts }}">
                    </div>
                </div>
            </div>








        </div>





    </div>


    <script>
        $(document).ready(function() {
            // Initialize counter-up
            $('.count-animation').each(function() {
                var $this = $(this),
                    countTo = $this.attr('data-count');

                $this.prop('Counter', 0).animate({
                    Counter: countTo
                }, {
                    duration: 2000, // Duration of the animation in milliseconds
                    easing: 'swing', // Easing function
                    step: function(now) {
                        $this.text('RS.' + Math.ceil(now).toLocaleString());
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initialize counter-up
            $('.count-animation-2').each(function() {
                var $this = $(this),
                    countTo = $this.attr('data-count');

                $this.prop('Counter', 0).animate({
                    Counter: countTo
                }, {
                    duration: 2000, // Duration of the animation in milliseconds
                    easing: 'swing', // Easing function
                    step: function(now) {
                        $this.text(+Math.ceil(now).toLocaleString());
                    }
                });
            });
        });
    </script>


@endsection
