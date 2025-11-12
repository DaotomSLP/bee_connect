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

        /*
        p {
            margin: 0px;
        } */

        .table {
            border-collapse: collapse;
            width: 100%;
            font-size: 9pt;
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
    <table style="width: 100%;">
        <tr>
            <td style="line-height:1.5;">
                <div>
                    <p style="font-size:10pt;">JSK Sole Co.,ltd</p>
                    <p style="font-size:10pt;">ໂທ : 020 2821 9546</p>
                    <p style="font-size:10pt;">Email :
                        jskgroup.lao@gmail.com</p>
                    <p style="font-size:10pt;">ບ້ານ ໄຊສະຫວ່າງ, ເມືອງ
                        ໄຊທານີ,</p>
                    <p style="font-size:10pt;">ນະຄອນຫຼວງວຽງຈັນ</p>
                </div>
            </td>
            <td>
                <p style="text-align: center; font-weight: bold; font-size: 16pt;">ໃບຮັບເງິນ</p>
            </td>
            <td style="text-align: right; line-height:1.5;">
                <p style="font-size: 10pt;">ຖ້ຽວທີ່ {{ $delivery_round->round }} ເດືອນ
                    {{ $delivery_round->month }}</p>

                <p style="font-size: 10pt;">ລົດວັນທີ :
                    {{ $delivery_round->departure_time }}</p>
                <p style="font-size: 10pt;">ສາຂາ : {{ $branch->branch_name }}</p>
            </td>
        </tr>
    </table>

    <table class="table">
        <tr>
            <th>
                ລ/ດ
            </th>
            <th>ລາຍການສິນຄ້າ</th>
            <th class="text-center">ນ້ຳໜັກ</th>
            <th class="text-center">ຂະໜາດ</th>
            <th class="text-right">ລາຄາ</th>
            <th class="text-right">ລວມ</th>
        </tr>
        <tbody>
            @php
                $sum_weight_kg = 0;
                $sum_weight_m = 0;
                $grand_total = 0;
            @endphp
            @foreach ($large_import_products as $key => $large_import_product)
                @php
                    if ($large_import_product->weight_type == 'kg') {
                        $sum_weight_kg += $large_import_product->weight;
                    } else {
                        $sum_weight_m += $large_import_product->weight;
                    }
                    $grand_total += $large_import_product->real_price * $large_import_product->weight;
                @endphp
                <tr>
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{ $large_import_product->code }}
                    </td>
                    <td class="text-center">
                        @if ($large_import_product->weight_type == 'kg')
                            {{ $large_import_product->weight }}
                            {{ $large_import_product->weight_type }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($large_import_product->weight_type == 'm')
                            {{ $large_import_product->weight }}
                            {{ $large_import_product->weight_type }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">
                        {{ number_format($large_import_product->real_price) }}
                        ກີບ/{{ $large_import_product->weight_type }}</td>
                    <td class="text-right">
                        {{ number_format($large_import_product->real_price * $large_import_product->weight) }}
                        ກີບ
                    </td>
                </tr>
            @endforeach
            @foreach ($normal_import_products as $key => $normal_import_product)
                @php
                    $sum_weight_kg += $normal_import_product->weight;
                    $grand_total += $normal_import_product->lot_base_price_kg * $normal_import_product->weight;
                @endphp
                <tr>
                    <td>
                        {{ sizeof($large_import_products) + $key + 1 }}
                    </td>
                    <td>
                        ລວມເຄື່ອງນ້ອຍ
                    </td>
                    <td class="text-center">{{ $normal_import_product->weight }} kg</td>
                    <td class="text-center">-</td>
                    <td class="text-right">
                        {{ number_format($normal_import_product->lot_base_price_kg) }}
                        ກີບ/kg</td>
                    <td class="text-right">
                        {{ number_format($normal_import_product->lot_base_price_kg * $normal_import_product->weight) }}
                        ກີບ
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" style="text-align: right;">
                    ລວມ
                </td>
                <td style="text-align: center;">
                    {{ $sum_weight_kg }} kg
                </td>
                <td style="text-align: center;">
                    {{ $sum_weight_m }} m
                </td>
                <td style="text-align: right; font-weight: bold;">ລວມຄ່າເຄື່ອງ
                </td>
                <td class="text-right" style="font-weight: bold;">
                    {{ number_format($grand_total) }}
                    ກີບ
                </td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td style="text-align: right; font-weight: bold;">ຄ່າສົ່ງ
                </td>
                <td class="text-right" style="font-weight: bold;">
                    {{ isset($sum_delivery_fee) ? number_format($sum_delivery_fee->fee) : 0 }}
                    ກີບ
                </td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td style="text-align: right; font-weight: bold;">ຄ່າເປົາ
                </td>
                <td class="text-right" style="font-weight: bold;">
                    {{ isset($sum_pack_fee) ? number_format($sum_pack_fee->pack_price) : 0 }}
                    ກີບ
                </td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td style="text-align: right; font-weight: bold;">ບໍລິການອື່ນໆ
                </td>
                <td class="text-right" style="font-weight: bold;">
                    {{ isset($sum_service_charge) ? number_format($sum_service_charge->service_charge) : 0 }}
                    ກີບ
                </td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td style="text-align: right; font-weight: bold; font-size: 11pt">ລວມທັງໝົດ
                </td>
                <td class="text-right" style="font-weight: bold; font-size: 11pt">
                    {{ number_format(
                        $grand_total +
                            (isset($sum_delivery_fee) ? $sum_delivery_fee->fee : 0) +
                            (isset($sum_pack_fee) ? $sum_pack_fee->pack_price : 0) +
                            (isset($sum_service_charge) ? $sum_service_charge->service_charge : 0),
                    ) }}
                    ກີບ
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
