<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Al-Karam Bags | Demands</title>
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
    <p style="text-align: center">-Shop # F-340 First Floor D-Point Plaza GT Road, Gujranwala</p>
    <p style="text-align: center"><strong>Husnain Nasir:-</strong> 0337-4967077</p>

    <h2 class="section-title">{{ $title }}</h2>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Demand Name</th>
                @if ($type=='parts')
               <th>Type</th>
                @endif

                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($demands as $key => $demand)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td title="{{ $demand->name }}">{{ $demand->name }}</td>
                       @if ($type=='parts')
 <td title="{{ $demand->item_type }}">{{ $demand->item_type }}</td>
                @endif

                    <td title="{{ $demand->qty ? $demand->qty : '---------' }}">
                        {{ $demand->qty ? $demand->qty : '---------' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
