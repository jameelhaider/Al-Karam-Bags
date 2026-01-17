<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Al-Karam Bags | Accounts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1.title-main {
            font-size: 28px;
            margin: 10px 0 5px;
            text-align: center;
            color: #2c3e50;
        }

        h2.title-sub {
            font-size: 18px;
            margin: 0 0 10px;
            text-align: center;
            color: #555;
        }

        hr {
            margin: 5px auto 10px;
            border: none;
            height: 1px;
            background: #ccc;
            width: 90%;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .table thead {
            background-color: #f4f4f4;
        }

        .table th,
        .table td {
            padding: 6px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            color: #555;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        td.center,
        th.center {
            text-align: center;
        }

        td.small {
            font-size: 10px;
        }

        td.medium {
            font-size: 12px;
        }
    </style>
</head>

<body>

    <h1 class="title-main">Al-Karam Bags</h1>
    <hr>
    <h2 class="title-sub">Accounts</h2>

    <table class="table">
        <thead>
            <tr>
                <th style="width:5%" class="center">#</th>
                <th style="width:10%" class="center">Acc ID</th>
                <th style="width:30%">Customer Name</th>
                <th style="width:18%">Phone</th>
                <th>Address</th>
                <th style="width:12%" class="center">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts as $key => $account)
                <tr>
                    <td class="center">{{ ++$key }}</td>
                    <td class="center">{{ $account->id }}</td>
                    <td class="medium">{{ $account->customer_name }}</td>
                    <td class="medium">{{ $account->customer_phone ?? '-------' }}</td>
                    <td class="small">{{ $account->customer_address ?? '-------' }}</td>
                    <td class="center medium">{{ 'Rs. ' . number_format($account->prev_balance) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
