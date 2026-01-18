<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Al-Karam Bags | {{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
            padding: 0;
            color: #333;
            font-size: 12px;
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
            padding: 6px 8px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .table th {
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            color: #444;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        h1, p {
            margin: 4px 0;
            line-height: 1.2;
        }

        .header-title {
            font-size: 28px;
            text-align: center;
            color: #444;
            margin-top: 0;
        }

        .section-title {
            font-size: 16px;
            text-align: center;
            color: #444;
            margin: 6px 0 10px;
        }
    </style>
</head>

<body>

    <h1 class="header-title">Al-Karam Bags</h1>
    <hr>
    <p style="text-align: center">~Shop No#80 Gali Chubakan Wali Sad Nagrii Bazaar Sialkoti Gate Gujranwala</p>
    <p style="text-align: center"><strong>Rana Awais:-</strong> 0315-8182213</p>
    <p style="text-align: center"><strong>Sabir Ali:-</strong> 0318-4499953</p>

    <h2 class="section-title">{{ $title }}</h2>

    <table class="table">
        <thead>
            <tr>
                <th style="text-align: center">#</th>
                <th>Demand Name</th>
                <th style="text-align: center">Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($demands as $key => $demand)
                <tr>
                    <td style="text-align: center">{{ ++$key }}</td>
                    <td title="{{ $demand->name }}">{{ $demand->name }}</td>
                    <td title="{{ $demand->qty ? $demand->qty : '---------' }}" style="text-align: center">
                        {{ $demand->qty ? $demand->qty : '---------' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
