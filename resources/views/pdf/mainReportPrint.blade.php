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
            max-width: 100px;
            height: auto;
            display: block;
            margin: auto;
        }
    </style>
</head>

<body>
    <p style="text-align: center; font-weight: bold; font-size: 16pt;">ລາຍງານລວມລາຍຮັບ-ລາຍຈ່າຍ</p>
    <p style="font-size: 12pt; text-align: right">ຖ້ຽວທີ່ {{ $delivery_round->round }} ເດືອນ {{ $delivery_round->month }}
        ລົດວັນທີ
        {{ $delivery_round->departure_time }}</p>

    <p style="font-size: 12pt; font-weight: bold; text-decoration: underline">ລາຍງານລາຍຮັບ :</p>
    <table class="table">
        <tr>
            <th style="width: 5px">
                ລ/ດ
            </th>
            <th>
                ຊື່ສາຂາ
            </th>
            <th>
                kg
            </th>
            <th>
                m3
            </th>
            <th>
                ຕົ້ນທຶນ
            </th>
            <th>
                ລວມຄ່າເຄື່ອງ
            </th>
            <th>
                ກຳໄລ
            </th>
            <th>
                ຄ່າຂົນສົ່ງ
            </th>
            <th>
                ຄ່າເປົາ
            </th>
            <th>
                ບໍລິການອື່ນໆ
            </th>
            <th>
                ລວມ
            </th>
            <th>
                ບິນຈ່າຍເງິນ
            </th>
            <th style="width: 100px">
                ໝາຍເຫດ
            </th>
        </tr>
        <tbody>
            @php
                $sumTotalBase = 0;
                $sumTotalPrice = 0;
                $sumTotalFee = 0;
                $sumTotalPack = 0;
                $sumTotalService = 0;
                $sumGrandTotal = 0;
                $sumKg = 0;
                $sumM3 = 0;
            @endphp
            @foreach ($bills as $key => $bill)
                @php
                    // Calculation for the sum
                    $sumTotalBase += $bill->total_base_price;
                    $sumTotalPrice += $bill->total_price;
                    $sumTotalFee += $bill->fee;
                    $sumTotalPack += $bill->pack_price;
                    $sumTotalService += $bill->service_charge;

                    // Calculate profit
                    $profit = $bill->total_price - $bill->total_base_price;

                    $total = $bill->total_price + $bill->fee + $bill->pack_price + $bill->service_charge;
                    $sumGrandTotal += $total;
                    $sumKg += $bill->weight_kg;
                    $sumM3 += $bill->weight_m;
                @endphp
                <tr class="table-body">
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $bill->branch_name }}</td>
                    <td class="text-center">
                        {{ $bill->weight_kg }}kg
                    </td>
                    <td class="text-center">
                        {{ $bill->weight_m }}m
                    </td>
                    <td class="text-right">{{ number_format($bill->total_base_price) }} ກີບ</td>
                    <td class="text-right">{{ number_format($bill->total_price) }} ກີບ</td>
                    <td class="text-right">{{ number_format($profit) }} ກີບ</td>
                    <td class="text-right">{{ number_format($bill->fee) }} ກີບ</td>
                    <td class="text-right">{{ number_format($bill->pack_price) }} ກີບ</td>
                    <td class="text-right">{{ number_format($bill->service_charge) }} ກີບ</td>
                    <td class="text-right">
                        {{ number_format($total) }}
                        ກີບ
                    </td>
                    <td class="text-center">
                        @if (!empty($bill->image_base64))
                            <img src="{{ $bill->image_base64 }}" alt="Receipt" class="receipt-image">
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td>

                    </td>
                </tr>
            @endforeach
            <!-- Summation Row for Income -->
            <tr class="grand-total">
                <td colspan="2" style="text-align: right; font-weight: bold;">ລວມທັງໝົດ (ລາຍຮັບ)</td>
                <td style="font-weight: bold;">
                    {{ $sumKg }}kg
                </td>
                <td style="font-weight: bold;">
                    {{ $sumM3 }}m
                </td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($sumTotalBase) }} ກີບ</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($sumTotalPrice) }} ກີບ</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($sumTotalPrice - $sumTotalBase) }}
                    ກີບ</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($sumTotalFee) }} ກີບ</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($sumTotalPack) }} ກີບ</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($sumTotalService) }} ກີບ</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($sumGrandTotal) }} ກີບ</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <p style="font-size: 12pt; font-weight: bold; text-decoration: underline">ລາຍການລາຍຈ່າຍ :</p>
    <table class="table">
        <tr>
            <th style="width: 5px">ລ/ດ</th>
            <th>ຫົວຂໍ້</th>
            <th>ວັນທີ</th>
            <th>ຈຳນວນເງິນ</th>
            <th>ບິນຈ່າຍເງິນ</th>
        </tr>
        <tbody>
            @php $sumExpenditurePrice = 0; @endphp
            @foreach ($expenditures as $key => $exp)
                @php $sumExpenditurePrice += $exp->price; @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $exp->detail }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($exp->created_at)) }}</td>
                    <td class="text-right">{{ number_format($exp->price) }} ກີບ</td>
                    <td>
                        @if (!empty($exp->image_base64))
                            <img src="{{ $exp->image_base64 }}" alt="Receipt" class="receipt-image">
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            <!-- Summation Row for Expenditure -->
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">ລວມທັງໝົດ (ລາຍຈ່າຍ)</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($sumExpenditurePrice) }} ກີບ</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 50px; padding: 15px; border: 1px solid #333;">
        @php
            // Calculate Net Total
            $netProfitAfterFee =
                $sumTotalPrice + $sumTotalFee + $sumTotalPack + $sumTotalService - $sumExpenditurePrice; // Total revenue (profit + fees) minus total expenses
        @endphp

        <p style="font-size: 14pt; font-weight: bold;">ສະຫຼຸບລວມຍອດ</p>
        <p style="font-size: 12pt;">ລວມຍອດລາຍຮັບ (ລວມຄ່າເຄື່ອງ, ຄ່າຂົນສົ່ງ, ຄ່າເປົາ, ຄ່າບໍລິການ) : <span
                style="font-weight: bold;">{{ number_format($sumTotalPrice + $sumTotalFee + $sumTotalPack + $sumTotalService) }}
                ກີບ</span></p>
        <p style="font-size: 12pt;">ລວມຍອດລາຍຈ່າຍທັງໝົດ : <span
                style="font-weight: bold;">{{ number_format($sumExpenditurePrice) }} ກີບ</span></p>
        <p style="font-size: 14pt; color: {{ $netProfitAfterFee >= 0 ? 'green' : 'red' }};">
            <span style="font-weight: bold; border-top: 2px solid #333; padding-top: 5px;">
                ຍອດເງິນຄົງເຫຼືອ/ກຳໄລສຸດທິ : {{ number_format($netProfitAfterFee) }} ກີບ
            </span>
        </p>
    </div>
</body>

</html>
