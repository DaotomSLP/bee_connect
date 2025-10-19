<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /* mPDF Font Definition: Uses absolute file path pointing to resources/fonts */


        @page {
            margin: 40px;
        }

        body {
            font-family: 'defago', sans-serif;
        }

        p {
            margin: 5px;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
            font-size: 11pt;
            margin-bottom: 40px;
        }

        .table td,
        .table th {
            border: 1px solid #ddd;
            padding: 8px;
            font-family: 'defago', sans-serif !important;
        }

        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #333;
            color: white;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Style for image receipt, ensuring it doesn't break layout */
        .receipt-image {
            max-width: 100px;
            height: auto;
            display: block;
            margin: auto;
        }
    </style>
</head>

<body>
    <p style="text-align: center; font-weight: bold; font-size: 16pt;">ລາຍງານລວມລາຍຮັບ-ລາຍຈ່າຍ</p>

    <p style="font-size: 12pt; font-weight: bold; text-decoration: underline">ລາຍງານລາຍຮັບ :</p>
    <table class="table">
        <tr>
            <th>ລ/ດ</th>
            <th>ລາຍການສິນຄ້າ</th>
            <th>ຫົວໜ່ວຍ</th>
            <th>ລາຄາ</th>
            <th>ລວມ</th>
        </tr>
        <tbody>
            @php
                $sumTotalBase = 0;
                $sumTotalPrice = 0;
                $sumTotalFee = 0;
                $sumTotalPack = 0;
                $sumTotalService = 0;
            @endphp
            @foreach ($import_products as $key => $import_product)
                @php
                    // Calculation for the sum
                    $sumTotalPrice += $import_product->total_real_price;
                @endphp
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>#{{ $import_product->code }}</td>
                    <td>{{ $import_product->weight_type }}</td>
                    <td class="text-right">{{ number_format($import_product->real_price) }} ກີບ</td>
                    <td class="text-right">{{ number_format($import_product->total_real_price) }} ກີບ</td>
                </tr>
            @endforeach
            <!-- Summation Row for Income -->
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">ລວມທັງໝົດ</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($sumTotalPrice) }} ກີບ</td>
                ກີບ</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
