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
            <th>ນ້ຳໜັກລວມ</th>
            <th>ລາຄາ</th>
            <th>ລວມ</th>
        </tr>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>ເຄື່ອງນ້ອຍ</td>
                <td class="text-center">{{ $lot->weight_kg }}</td>
                <td class="text-right">{{ number_format($lot->lot_real_price_kg) }} ກີບ</td>
                <td class="text-right">{{ number_format($lot->total_price) }} ກີບ</td>
            </tr>
            <!-- Summation Row for Income -->
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">ລວມຄ່າເຄື່ອງ</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($lot->total_price) }} ກີບ</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;">ຄ່າສົ່ງ</td>
                <td class="text-right">{{ number_format($lot->fee) }} ກີບ</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;">ຄ່າເປົາ</td>
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
                    <td colspan="4" style="text-align: right;">{{ $service_charge->name }}</td>
                    <td class="text-right">{{ number_format($service_charge->price) }} ກີບ</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">ລວມທັງໝົດ</td>
                <td class="text-right" style="font-weight: bold;">
                    {{ number_format($lot->total_main_price) }} ກີບ
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
