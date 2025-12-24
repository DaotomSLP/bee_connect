<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        body {
            font-family: 'defago', sans-serif;
        }

        #itemtable {
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
            font-size: 12pt;
            font-family: 'Saysettha OT';
        }

        @page {
            margin: 20px;
        }

        .logo-image {
            width: 70px;
            display: block;
            margin: auto;
        }
    </style>
</head>

<body>
    @if (!empty($branch->logo_image))
        <div style="text-align: center; margin-bottom: 10px;">
            <img src="{{ $_SERVER['DOCUMENT_ROOT'] . '/img/logos/' . $branch->logo_image }}" alt="Logo" class="logo-image">
        </div>
    @endif
    <p style="text-align: center;font-weight: bold;font-size: 14pt;margin: 0px">ໃບນຳສົ່ງສິນຄ້າ</p>
    <p style="font-size: 14pt;margin-bottom: 0px; font-weight: bold;">ເລກບິນ :
        {{ $id }}
    </p>
    <p style="font-size: 12pt;margin: 0px">ວັນທີ :
        {{ date('d-m-Y', strtotime($date)) }}
    </p>

    <hr>

    <table id="itemtable">
        <tr>
            <th>ລະຫັດເຄື່ອງ</th>
            <th>ນ້ຳໜັກ</th>
            <th>ລາຄາ</th>
            <th>ລວມ</th>
        </tr>
        @foreach ($items as $item)
            <tr>
                <td>{{ $item->code }}</td>
                <td>{{ $item->weight_branch ? $item->weight_branch : $item->weight_branch }}
                    {{ $item->weight_type == 'm' ? 'm' : 'kg' }}</td>
                <td>{{ number_format($item->sale_price) }}</td>
                <td>{{ number_format($item->sale_price * ($item->weight_branch ?? $item->weight) + ($item->shipping_fee ? $item->shipping_fee : 0)) }}
                </td>
            </tr>
        @endforeach
    </table>
    <hr>
    @if ($discount > 0)
        <p style="font-size: 12pt;margin: 0px; margin-bottom:10px; ">ສ່ວນຫຼຸດ :
            {{ number_format($discount) }} ກີບ
        </p>
    @endif
    <p style="font-size: 14pt;margin-top:5px;font-weight: bold;">ລວມເປັນເງິນ :
        {{ number_format($price) }} ກີບ
    </p>

</body>

</html>
