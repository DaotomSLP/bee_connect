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
    <p style="text-align: center; font-weight: bold; font-size: 16pt;">ລາຍການເຄື່ອງບໍ່ມີຊື່</p>
    <table class="table">
        <tr class="font-weight-bold">
            <th>
                ລ/ດ
            </th>
            <th>
                ລະຫັດເຄື່ອງ
            </th>
            <th>
                ຮັບມາວັນທີ່
            </th>
            <th>
                ຂະໜາດ
            </th>
            <th>
                ສະຖານະ
            </th>
        </tr>
        <tbody>
            @foreach ($lost_products as $key => $lost_product)
                <tr>
                    <td class="text-center">
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{ $lost_product->code }}
                    </td>
                    <td class="text-center">
                        {{ $lost_product->created_at ? date('d-m-Y', strtotime($lost_product->created_at)) : '' }}
                    </td>
                    <td class="text-center">
                        {{ $lost_product->weight }}
                    </td>
                    <td class="text-center">
                        @if ($lost_product->status == 'success')
                            ປ່ອຍອອກ
                        @else
                            ຄ້າງ
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
