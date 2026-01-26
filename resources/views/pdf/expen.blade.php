<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin: 40px;
        }

        body {
            font-family: 'defago', sans-serif;
        }

        p {
            margin: 5px;
        }

        #table {
            border-collapse: collapse;
            width: 100%;
            font-family: 'defago', sans-serif;
            font-size: '12pt'
        }

        #table td,
        #table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #table tr:hover {
            background-color: #ddd;
        }

        #table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #333;
            color: white;
        }

        .receipt-image {
            max-width: 80px;
            height: auto;
            display: block;
            margin: auto;
        }
    </style>
</head>

<body>
    <p style="text-align: center;font-weight: bold;font-size: 16pt;">ລາຍງານລາຍຈ່າຍ</p>
    <p style="font-size: 12pt; text-align: right">ປະຈຳຖ້ຽວທີ
        {{ $delivery_round->round }} ເດືອນ {{ $delivery_round->month }} ລົດວັນທີ
        {{ $delivery_round->departure_time }}
    </p>
    <p style="font-size: 12pt; text-align: right; margin-top: 0">ສ້າງລາຍງານໂດຍ :
        {{ auth()->user()->name }}
    </p>

    <p style="font-size: 12pt; font-weight: bold; text-decoration: underline">ລາຍການລາຍຈ່າຍ :</p>

    <table id="table">
        <tr>
            <th>
                ລ/ດ
            </th>
            <th>
                ວັນທີ
            </th>
            <th>
                ຈຳນວນເງິນ
            </th>
            <th>
                ລາຍລະອຽດ
            </th>
            <th>
                ບິນຈ່າຍເງິນ
            </th>
            <th>
                ເພີ່ມໂດຍ
            </th>
        </tr>
        <tbody>
            @foreach ($expenditure as $key => $expen)
                <tr>
                    <td style="width: 5px">
                        {{ $key + 1 }}
                    </td>
                    <td style="width: 100px">
                        {{ date('d-m-Y', strtotime($expen->created_at)) }}
                    </td>
                    <td style="width: 140px; text-align: right;">
                        {{ number_format($expen->price) }} ກີບ
                    </td>
                    <td>
                        {{ $expen->detail }}
                    </td>
                    <td class="text-center" style="width: 100px">
                        @if (!empty($expen->receipt_image))
                            <img src="{{ $_SERVER['DOCUMENT_ROOT'] . '/img/receipts/' . $expen->receipt_image }}"
                                alt="Receipt" class="receipt-image">
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td style="width: 80px">
                        {{ $expen->name }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" style="font-weight: bold">
                    ລວມເປັນເງິນ
                </td>
                <td colspan="4" style="font-weight: bold; text-align: center">
                    {{ number_format($totalExpenditure) }} ກີບ
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
