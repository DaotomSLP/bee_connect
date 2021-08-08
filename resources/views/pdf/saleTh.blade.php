<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        #itemtable {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #itemtable td,
        #itemtable th {
            border: 1px solid #000;
            padding: 8px;
        }

        #itemtable tr:hover {}

        #itemtable th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            color: black;
        }

        body {
            font-size: 7pt;
            font-family: 'Saysettha OT';
        }

        @page {
            margin: 20px;
        }

    </style>
</head>

<body>
    <p style="text-align: center;font-weight: bold;font-size: 12pt;margin: 1px">ໃບນຳສົ່ງສິນຄ້າ</p>
    <p style="font-size: 12pt;margin: 5px">ເລກບິນ :
        {{ $id }}
    </p>
    <p style="font-size: 10pt;margin: 0px">ວັນທີ :
        {{ date('d-m-Y', strtotime($date)) }}
    </p>

    <hr>

    <table id="itemtable">
        <tr>
            <th>ລະຫັດເຄື່ອງ</th>
            <th>ຂະໜາດ/CM</th>
            <th>ລາຄາ</th>
            <th>ຄ່າສົ່ງ</th>
            <th>ລວມ</th>
        </tr>
        @foreach ($items as $item)
            <tr>
                <td>{{ $item->code }}</td>
                <td>{{ $item->weight }}</td>
                <td>{{ number_format($item->sale_price) }}</td>
                <td>{{ number_format($item->shipping_fee ? $item->shipping_fee : 0) }}</td>
                <td>{{ number_format($item->total_sale_price + ($item->shipping_fee ? $item->shipping_fee : 0)) }}
                </td>
            </tr>
        @endforeach
    </table>
    <hr>
    @if ($discount > 0)
        <p style="font-size: 10pt;margin: 0px; margin-bottom:10px; ">ສ່ວນຫຼຸດ :
            {{ number_format($discount) }} ບາດ
        </p>
    @endif
    <p style="font-size: 12pt;margin-top:5px">ລວມເປັນເງິນ :
        {{ number_format($price) }} ບາດ
    </p>

</body>

</html>
