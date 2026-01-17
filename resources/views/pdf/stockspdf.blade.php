<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Al-Karam Bags | {{ $title }}</title>



    <style>
        html,
        body {
            padding: 0;
            margin: 5px;
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 12px;
        }

        .fw-bold {
            font-weight: bold;
        }

        .container {
            width: 100%;
        }

        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            color: #444;
        }

        h2 {
            font-size: 18px;
            margin-top: 30px;
            color: #222;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-top: 5px;
        }

        .table th,
        .table td {
            padding: 4px;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f4f4f4;
            text-transform: uppercase;
            font-size: 10px;
            color: #555;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            font-size: 8px;
            border-radius: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bg-success {
            background-color: rgb(1, 149, 1);
            color: white;
        }

        .bg-warning {
            background-color: rgb(255, 162, 0);
            color: white;
        }

        .bg-danger {
            background-color: rgb(253, 27, 27);
            color: white;
        }
           .header-title {
            font-size: 28px;
            text-align: center;
            color: #444;
            margin-top: 0;
        }
    </style>


</head>

<body>
    <div class="container">
        <h1 class="header-title">Al-Karam Bags</h1>
        <hr>
        <p style="text-align: center">-Shop # F-340 First Floor D-Point Plaza GT Road, Gujranwala</p>
        <p style="text-align: center"><strong>Husnain Nasir:-</strong> 0337-4967077</p>
        @foreach ($groups as $group)
            <h2>{{ $group['type_name'] }}</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        @if ($show_price)
                            <th>Price</th>
                        @endif
                        @if ($show_qty)
                            <th>Qty</th>
                        @endif
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($group['stocks'] as $key => $stock)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-size: 13px" title="{{ $stock->name }}">
                                {{ $stock->name }}
                            </td>

                            @if ($show_price)
                                <td class="fw-bold text-dark" style="font-size: 15px"
                                    title="Rs.{{ number_format($stock->sale_price) }}">
                                    Rs.{{ number_format($stock->sale_price) }}
                                </td>
                            @endif

                            @if ($show_qty)
                                <td style="font-size: 13px" title="{{ $stock->qty }}">
                                    {{ $stock->qty }}
                                </td>
                            @endif

                            <td>
                                @if ($stock->qty > 0)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Out Of Stock</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 3 + ($show_price ? 1 : 0) + ($show_qty ? 1 : 0) }}"
                                style="text-align:center;">
                                No records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endforeach
    </div>
</body>

</html>
