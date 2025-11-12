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
            margin: 0px;
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
    <p style="text-align: left; font-size: 12pt;">JSK Sole Co.,ltd</p>
    <p style="text-align: left; font-size: 12pt;">ໂທ : 020 2821 9546</p>
    <p style="text-align: left; font-size: 12pt;">Email : jskgroup.lao@gmail.com</p>
    <p style="text-align: left; font-size: 12pt;">ບ້ານ ໄຊສະຫວ່າງ, ເມືອງ ໄຊທານີ, ນະຄອນຫຼວງວຽງຈັນ</p>
    <p style="text-align: center; font-weight: bold; font-size: 16pt;">ໃບຮັບເງິນ</p>
    <p style="text-align: right; font-size: 12pt;">ເລກທີ : #{{ $lot->id }}</p>
    <p style="text-align: right; font-size: 12pt;">ວັນທີ : {{ date('d-m-Y', strtotime($lot->created_at)) }}</p>
    <p style="text-align: right; font-size: 12pt;">ສາຂາ : {{ $lot->branch_name }}</p>

    <table class="table">
        <tr>
            <th>ລ/ດ</th>
            <th>ລາຍການສິນຄ້າ</th>
            <th>ຂະໜາດ/ນ້ຳໜັກ</th>
            <th>ຫົວໜ່ວຍ</th>
            <th>ລາຄາ</th>
            <th>ລວມ</th>
        </tr>
        <tbody>
            @php
                $sumTotalPrice = 0;
                $sumTotalSize = 0;
            @endphp
            @foreach ($import_products as $key => $import_product)
                @php
                    // Calculation for the sum
                    $sumTotalPrice += $import_product->real_price * $import_product->weight;
                    $sumTotalSize += $import_product->weight;
                @endphp
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>#{{ $import_product->code }}</td>
                    <td class="text-center">{{ $import_product->weight }}</td>
                    <td class="text-center">{{ $import_product->weight_type }}</td>
                    <td class="text-right">{{ number_format($import_product->real_price) }} ກີບ</td>
                    <td class="text-right">{{ number_format($import_product->real_price * $import_product->weight) }}
                        ກີບ
                    </td>
                </tr>
            @endforeach
            <!-- Summation Row for Income -->
            <tr>
                <td colspan="2"></td>
                <td class="text-center" style="font-weight: bold;">{{ $sumTotalSize }}</td>
                <td colspan="2" style="text-align: right; font-weight: bold;">ລວມຄ່າເຄື່ອງ</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($sumTotalPrice) }} ກີບ</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td></td>
                <td colspan="2" style="text-align: right;">ຄ່າສົ່ງ</td>
                <td class="text-right">{{ number_format($lot->fee) }} ກີບ</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td></td>
                <td colspan="2" style="text-align: right;">ຄ່າເປົາ</td>
                <td class="text-right">{{ number_format($lot->pack_price) }} ກີບ</td>
            </tr>
            @php
                $sumTotalServiceCharge = 0;
            @endphp
            @foreach ($service_charges as $service_charge)
                @php
                    $sumTotalServiceCharge += $service_charge->price;
                @endphp
                <tr>
                    <td colspan="2"></td>
                    <td></td>
                    <td colspan="2" style="text-align: right;">{{ $service_charge->name }}</td>
                    <td class="text-right">{{ number_format($service_charge->price) }} ກີບ</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold;">ລວມທັງໝົດ</td>
                <td class="text-right" style="font-weight: bold;">
                    {{ number_format($sumTotalPrice + $lot->fee + $lot->pack_price + $sumTotalServiceCharge) }} ກີບ
                </td>
            </tr>
        </tbody>
    </table>

    <table style="width: 100%">
        <tbody>
            <tr>
                <td class="text-center">
                    <p>ຜູ້ຈ່າຍເງິນ</p>
                    <p>(ໄດ້ຮັບເຄື່ອງຄົບແລ້ວ)</p>
                </td>
                <td class="text-center">
                    <p>ຜູ້ອະນຸມັດ</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
