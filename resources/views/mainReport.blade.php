    @extends('layout')

    @section('body')
        <!-- End Navbar -->
        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>ລາຍງານປະຈຳວັນ</h3>
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr>
                <div class="row">
                    <div class="col">
                        <h5>ປະຈຳວັນທີ :</h5>
                        <form method="GET" action="mainReport">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-12">
                                    <div class="form-group">
                                        <input class="form-control" type="date" value="{{ $date_now }}"
                                            name="date" id="date">
                                    </div>
                                </div>
                                <p class="h5">ຫາ</p>
                                <div class="col-lg-4 col-md-4 col-12">
                                    <div class="form-group">
                                        <input class="form-control" type="date" value="{{ $to_date_now }}"
                                            name="to_date" id="to_date">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary pull-right px-5">ຄົ້ນຫາ</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div>
                                <h2 class="card-title ">ລາຍງານລາຍຮັບ</h2>
                            </div>
                            <div class="x_content">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class=" text-primary">
                                            <th>
                                                ລ/ດ
                                            </th>
                                            <th>
                                                ເລກບິນ
                                            </th>
                                            <th>
                                                ຊື່ສາຂາ
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
                                                ຄ່າບໍລິການອື່ນໆ
                                            </th>
                                            <th>
                                                ບິນຈ່າຍເງິນ
                                            </th>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sumTotalBase = 0;
                                                $sumTotalPrice = 0;
                                                $sumTotalProfit = 0;
                                                $sumTotalFee = 0;
                                                $sumTotalPack = 0;
                                                $sumTotalService = 0;
                                            @endphp
                                            @foreach ($lots as $key => $lot)
                                                @php
                                                    $sumTotalBase += $lot->total_base_price;
                                                    $sumTotalPrice += $lot->total_price;
                                                    $sumTotalProfit += $lot->total_price - $lot->total_base_price;
                                                    $sumTotalFee += $lot->fee;
                                                    $sumTotalPack += $lot->pack_price;
                                                    $sumTotalService += $lot->service_charge;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{ $key + 1 }}
                                                    </td>
                                                    <td>
                                                        #{{ $lot->id }}
                                                    </td>
                                                    <td>
                                                        {{ $lot->branch_name }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($lot->total_base_price) }}
                                                        ກີບ
                                                    </td>
                                                    <td>
                                                        {{ number_format($lot->total_price) }}
                                                        ກີບ
                                                    </td>
                                                    <td>
                                                        {{ number_format($lot->total_price - $lot->total_base_price) }}
                                                        ກີບ
                                                    </td>
                                                    <td>
                                                        {{ number_format($lot->fee) }}
                                                        ກີບ
                                                    </td>
                                                    <td>
                                                        {{ number_format($lot->pack_price) }}
                                                        ກີບ
                                                    </td>
                                                    <td>
                                                        {{ number_format($lot->service_charge) }}
                                                        ກີບ
                                                    </td>
                                                    <td>
                                                        @if ($lot->receipt_image)
                                                            <img src="{{ '/img/receipts/' . $lot->receipt_image }}"
                                                                alt="Receipt Image"
                                                                style="max-width: 100px; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.2);">
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="3">
                                                    <p class="text-center h6 font-weight-bold">
                                                        ລວມ :
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="h6 font-weight-bold">
                                                        {{ number_format($sumTotalBase) }}
                                                        ກີບ
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="h6 font-weight-bold">
                                                        {{ number_format($sumTotalPrice) }}
                                                        ກີບ
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="h6 font-weight-bold">
                                                        {{ number_format($sumTotalPrice - $sumTotalBase) }}
                                                        ກີບ
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="h6 font-weight-bold">
                                                        {{ number_format($sumTotalFee) }}
                                                        ກີບ
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="h6 font-weight-bold">
                                                        {{ number_format($sumTotalPack) }}
                                                        ກີບ
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="h6 font-weight-bold">
                                                        {{ number_format($sumTotalService) }}
                                                        ກີບ
                                                    </p>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div>
                                <h2 class="card-title ">ລາຍງານລາຍຈ່າຍ</h2>
                            </div>
                            <div class="x_content">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class=" text-primary">
                                            <th>
                                                ລ/ດ
                                            </th>
                                            <th>
                                                ຫົວຂໍ້
                                            </th>
                                            <th>
                                                ວັນທີ
                                            </th>
                                            <th>
                                                ຈຳນວນເງິນ
                                            </th>
                                            <th>
                                                ບິນຈ່າຍເງິນ
                                            </th>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sumExpenditurePrice = 0;
                                            @endphp
                                            @foreach ($expenditures as $key => $expenditure)
                                                @php
                                                    $sumExpenditurePrice += $expenditure->price;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{ $key + 1 }}
                                                    </td>
                                                    <td>
                                                        {{ $expenditure->detail }}
                                                    </td>
                                                    <td>
                                                        {{ date('d-m-Y', strtotime($expenditure->created_at)) }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($expenditure->price) }}
                                                        ກີບ
                                                    </td>
                                                    <td>
                                                        @if ($expenditure->receipt_image)
                                                            <img src="{{ '/img/receipts/' . $expenditure->receipt_image }}"
                                                                alt="Receipt Image"
                                                                style="max-width: 100px; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.2);">
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="3">
                                                    <p class="text-center h6 font-weight-bold">
                                                        ລວມ :
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="h6 font-weight-bold">
                                                        {{ number_format($sumExpenditurePrice) }}
                                                        ກີບ
                                                    </p>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_content">
                                <p class="h5 text-dark font-weight-bold">
                                    ສະຫຼຸບລວມຍອດ :
                                </p>
                                <div>
                                    <p class="h6 text-dark">
                                        ລວມຍອດລາຍຮັບ (ລວມຄ່າເຄື່ອງ, ຄ່າຂົນສົ່ງ, ຄ່າເປົາ, ຄ່າບໍລິການ) :
                                    </p>
                                    <p class="font-weight-bold h6 text-success">
                                        {{ number_format($sumTotalPrice + $sumTotalFee + $sumTotalPack + $sumTotalService) }}
                                        ກີບ
                                    </p>
                                </div>
                                <hr />
                                <div>
                                    <p class="h6 text-dark font-weight-bold">
                                        ລວມຍອດລາຍຈ່າຍທັງໝົດ :
                                    </p>
                                    <p class="font-weight-bold h6 text-danger">
                                        {{ number_format($sumExpenditurePrice) }} ກີບ
                                    </p>
                                </div>
                                <hr />
                                <div>
                                    <p class="h5 text-dark font-weight-bold">
                                        ຍອດເງິນຄົງເຫຼືອ/ກຳໄລສຸດທິ :
                                    </p>
                                    <p class="font-weight-bold h5 text-success">
                                        {{ number_format($sumTotalPrice + $sumTotalFee + $sumTotalPack + $sumTotalService - $sumExpenditurePrice) }}
                                        ກີບ
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_content">
                                <div>
                                    <a href="{{ url('mainReportPrint?date=' . $date_now . '&to_date=' . $to_date_now) }}"
                                        target="_blank" class="btn btn-primary text-white px-5">
                                        ພິມລາຍງານ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- jQuery -->
        <script src="../vendors/jquery/dist/jquery.min.js"></script>
        <!-- Chart.js -->
        <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
    @endsection
