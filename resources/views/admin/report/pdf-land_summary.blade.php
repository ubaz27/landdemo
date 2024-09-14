<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $filename ?? 'Statement' }}</title>
    <style>
        table {
            font-size: 12px;
            border-collapse: collapse;
            width: 100%;
            /* table-layout: fixed; */
            /* white-space:  nowrap !important; */
        }

        td,
        th {
            /* border: 1px solid black; */
            padding: 10px;
            /* white-space:  nowrap !important; */
        }

        th {
            border-bottom: 1px solid black;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        @page {
            footer: page-footer;
        }

        .statement-header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px
        }
    </style>
</head>

<body>
    <div class="statement-header"><img src="{{ asset('dist/img/logo.png') }}" height="70px" /></div>
    <div class="statement-header">ss</div>
    <div class="statement-header">
        {{ $lands->land_name ?? '' }}
        {{ $lands->cost . ' LGA: ' . $lands->lga }}
    </div>
    <div class="statement-header">
        From {{ $sdate }} To {{ $edate }}
    </div>

    <table style="margin-top:  10px; width:  100%">
        <thead>
            <tr>
                <th style="width: 80px">S/N</th>
                <th style="width: 80px">Phone</th>
                <th style="text-align:left">Name</th>
                <th style="text-align:left">Plot No</th>

                <th style="width: 150px; text-align:right">Dimension(<span>&#8358;</span>)</th>
                <th style="width: 150px; text-align:right">Plot Cost(<span>&#8358;</span>)</th>
                <th style="width: 150px; text-align:right">Amount(<span>&#8358;</span>)</th>
            </tr>
        </thead>
        <tbody style="border: solid">
            {{ $id = 1 }}
            {{ $investment_balance = 0 }}
            {{ $savings_balance = 0 }}
            {{ $RSA_balance = 0 }}
            {{ $Target_balance = 0 }}
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $id }}</td>
                    <td>{{ $transaction->phone }}</td>
                    <td>{{ $transaction->name }}</td>
                    <td style="text-align:right">{{ $transaction->plot_no }}</td>
                    <td style="text-align:right">{{ $transaction->dimension }}</td>
                    <td style="text-align:right">{{ $transaction->plot_cost }}</td>
                    <td style="text-align:right">{{ $transaction->amount }}</td>

                    {{ $balance = +$transaction->amount }}

                    {{-- <td style="text-align:right">{{ number_format($balance, 2) }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>



    <div style="font-size:12px; margin-top:20px">
        TOTAL TRANSACTIONS: <span>&#8358;</span>{{ number_format($balance, 2) }} <br>
        {{-- TOTAL DEBIT : <span>&#8358;</span>{{ number_format($g_total_debit, 2) }}<br>
        GRAND TOTAL: <span>&#8358;</span>{{ number_format($g_total_credit - $g_total_debit, 2) }} <br> --}}
    </div>

</body>

</html>
