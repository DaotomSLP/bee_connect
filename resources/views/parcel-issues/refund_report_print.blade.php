<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /* mPDF Font Definition: Uses absolute file path pointing to resources/fonts */


        @page {
            margin: 20px;
        }

        body {
            font-family: 'defago', sans-serif;
        }

        p {
            margin: 5px;
        }

        .table {
            border-collapse: collapse;
            border-color: #000;
            width: 100%;
            margin-bottom: 40px;
        }

        .table td,
        .table th {
            border: 1px solid #333;
            padding: 3px;
            font-family: 'defago', sans-serif !important;
            font-size: 9pt;
        }

        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table th {
            padding-top: 12px;
            padding-bottom: 12px;
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
            max-width: 80px;
            height: auto;
            display: block;
            margin: auto;
        }
    </style>
</head>

<body>
    <p style="text-align: center; font-weight: bold; font-size: 16pt;">ລາຍງານການຈ່າຍຄ່າເຄື່ອງເສຍ</p>
    <p style="font-size: 12pt; text-align: right">ປະຈຳວັນທີ
        {{ isset($startDate) ? date('d-m-Y', strtotime($startDate)) : '' }} ເຖິງ
        {{ isset($endDate) ? date('d-m-Y', strtotime($endDate)) : '' }}</p>

    <p style="font-size: 12pt; font-weight: bold; text-decoration: underline">ລາຍການລາຍຈ່າຍ :</p>
    <table class="table">
        <tr>
            <th style="width: 5px">ລ/ດ</th>
            <th>
                ວັນທີ
            </th>
            <th>
                ຈຳນວນເງິນ
            </th>
            <th>
                ລະຫັດເຄື່ອງ
            </th>
            <th>
                ໝາຍເຫດ
            </th>
            <th>
                ເຄື່ອງວັນທີ
            </th>
            <th>
                ຈາກສາຂາ
            </th>
            <th>
                ບິນຈ່າຍເງິນ
            </th>
        </tr>
        <tbody>
            @php $sumRefund = 0; @endphp
            @foreach ($refunds as $key => $refund)
                @php $sumRefund += $refund->amount; @endphp
                <tr>
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{ date('d-m-Y', strtotime($refund->created_at)) }}
                    </td>
                    <td class="text-right">
                        {{ number_format($refund->amount) }}
                        ກີບ
                    </td>
                    <td class="text-center">
                        {{ $refund->parcel_code }}
                    </td>
                    <td>
                        {{ $refund->detail }}
                    </td>
                    <td class="text-center">
                        {{ date('d-m-Y', strtotime($refund->received_at)) }}
                    </td>
                    <td class="text-center">
                        {{ $refund->branch_name }}
                    </td>
                    <td>
                        @if (!empty($refund->receipt_image))
                            <img src="{{ $_SERVER['DOCUMENT_ROOT'] . '/img/receipts/' . $refund->receipt_image }}"
                                alt="Receipt" class="receipt-image">
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            <!-- Summation Row for Expenditure -->
            <tr>
                <td colspan="2">
                    <p class="text-center h6 font-weight-bold">
                        ລວມ :
                    </p>
                </td>
                <td>
                    <p class="h6 font-weight-bold">
                        {{ number_format($sumRefund) }}
                        ກີບ
                    </p>
                </td>
                <td colspan="5">
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
