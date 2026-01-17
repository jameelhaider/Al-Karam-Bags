<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Al-Karam Bags | Cash Receiveds</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('uploads/c2.png') }}" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
            color: #333;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }

        .table thead {
            background-color: #f4f4f4;
        }

        .table th,
        .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
            color: #555;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-dark {
            color: #333;
        }

        .text-muted {
            color: #888;
        }

        .fw-normal {
            font-weight: normal;
        }

        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            color: #444;
        }
    </style>
</head>

<body>



    <h1>Cash Receiveds</h1>
    <table class="table">
        <thead>
            <tr>
                <th style="width:3%">#</th>
                <th style="width:3%">Acc ID</th>
                <th style="width:22%">Customer Name</th>
                <th style="width:12%">Narration</th>
                <th style="width:10%">Amount Received</th>
                <th style="width:10%">R.A.R</th>
                <th style="width:20%">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key=> $dta)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $dta->account_id }}</td>
                    <td style="font-size: 13px;">
                        {{ $dta->customer_name }}
                    </td>
                    <td style="font-size: 13px;">
                        {{ $dta->narration }}
                    </td>
                    <td style="font-size: 13px;">
                        {{ 'Rs.'.number_format($dta->ammount) }}
                    </td>
                    <td style="font-size: 13px;">
                        {{ 'Rs.'.number_format($dta->final_rem) }}
                    </td>
                    <td style="font-size: 13px;">
                        {{ \Carbon\Carbon::parse($dta->created_at)->format('F d, Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
