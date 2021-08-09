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

        td {
            font-size: 9pt;
        }

        @page {
            margin: 10px;
        }

        p {
            margin: 5px
        }

    </style>
</head>

<body>
    <p style="text-align: center;font-weight: bold;font-size: 20pt;">BEE CONNECT</p>

    <p style="text-align: center;font-weight: bold;font-size: 12pt;">ໃບນຳສົ່ງສິນຄ້າ</p>

    <p style="font-size: 12pt;font-weight: bold;">ເລກບິນ :
        {{ $id }}
    </p>

    <p style="font-size: 11pt;">ວັນທີ :
        {{ $date }}
    </p>

    <p style="font-size: 11pt;">ເຖິງສາຂາ :
        {{ $to }}
    </p>

    <!-- <p style="font-size: 11pt;">ນ້ຳໜັກລວມ :
        {{ $weight_kg }} kg
    </p> -->


    @if ($fee)
        <p style="font-size: 11pt;">ຄ່າຂົນສົ່ງ :
            {{ number_format($fee) }} ກີບ
        </p>
    @endif
    @if ($pack_price)
        <p style="font-size: 11pt;">ຄ່າເປົາ :
            {{ number_format($pack_price) }} ກີບ
        </p>
    @endif

    <p style="font-size: 12pt;font-weight: bold;">ລາຍການ :</p>

    <table id="itemtable">
        <tr>
            <th>
                ລ/ດ
            </th>
            <th>
                ລະຫັດເຄື່ອງ
            </th>
            <th>
                ລາຄາ
            </th>
        </tr>
        @foreach ($import_product_data as $key => $item)
            <tr>
                <td>
                    {{ $key }}
                </td>
                <td>
                    {{ $item->code }}
                </td>
                <td>
                    {{ $item->real_price }}
                </td>
            </tr>
        @endforeach
    </table>

    <hr>
    <p style="font-size: 12pt;">ລວມເປັນເງິນ :
        {{ number_format($price) }} ບາດ
    </p>

</body>

</html>
