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
                        <form method="GET" action="dailyImport">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-12">
                                    <div class="form-group">
                                        <input class="form-control" type="date" value="{{ $date_now }}" name="date">
                                    </div>
                                </div>
                                <p class="h5">ຫາ</p>
                                <div class="col-lg-4 col-md-4 col-12">
                                    <div class="form-group">
                                        <input class="form-control" type="date" value="{{ $to_date_now }}"
                                            name="to_date">
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
                @if (Auth::user()->branch_id != null)
                    <div class="row">
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel bg-primary text-white">
                                <div>
                                    <p class="h4">ຈຳນວນເງິນທີ່ໄດ້ຮັບ</p>
                                </div>
                                <hr class="border-white">
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_real_price) }} ກີບ</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel bg-warning text-white">
                                <div>
                                    <p class="h4">ຕົ້ນທຶນ</p>
                                </div>
                                <hr class="border-white">
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_base_price) }} ກີບ</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel bg-success text-white">
                                <div>
                                    <p class="h4">ກຳໄລການຂາຍ</p>
                                </div>
                                <hr class="border-white">
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_sale_profit) }} ກີບ</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel bg-info text-white">
                                <div>
                                    <p class="h4">ນ້ຳໜັກລວມ (kg)</p>
                                </div>
                                <hr class="border-white">
                                <div class="x_content">
                                    <p class="h2">{{ $sum_weight_kg_branch }} Kg</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel bg-danger text-white">
                                <div>
                                    <p class="h4">ຂະໜາດລວມ (m)</p>
                                </div>
                                <hr class="border-white">
                                <div class="x_content">
                                    <p class="h2">{{ $sum_weight_m_branch }} m</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel bg-primary text-white">
                                <div>
                                    <p class="h4">ຈຳນວນເງິນທີ່ໄດ້ຮັບ</p>
                                </div>
                                <hr class="border-white">
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_real_price) }} ກີບ</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel bg-warning text-white">
                                <div>
                                    <p class="h4">ຕົ້ນທຶນ</p>
                                </div>
                                <hr class="border-white">
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_base_price) }} ກີບ</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel bg-success text-white">
                                <div>
                                    <p class="h4">ກຳໄລການຂາຍ</p>
                                </div>
                                <hr class="border-white">
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_sale_profit) }} ກີບ</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel bg-info text-white">
                                <div>
                                    <p class="h4">ລວມຄ່າຂົນສົ່ງ</p>
                                </div>
                                <hr class="border-white">
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_fee_price) }} ກີບ</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel">
                                <div>
                                    <p class="h4">ລວມຄ່າເປົາ</p>
                                </div>
                                <hr>
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_pack_price) }} ກີບ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel bg-danger text-white">
                                <div>
                                    <p class="h4 d-inline">ລາຍຈ່າຍອື່ນໆ</p>
                                    <p class="pl-3 h4 d-inline"><a href="/expenditure"><i
                                                class="fa fa-arrow-right text-white"></i></span></a></p>
                                </div>
                                <hr class="border-white">
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_expenditure) }} ກີບ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel bg-primary text-white">
                                <div>
                                    <p class="h3 font-weight-bold">ກຳໄລ</p>
                                </div>
                                <hr class="border-white">
                                <div class="x_content">
                                    <p class="h2 font-weight-bold">{{ number_format($sum_profit) }} ກີບ</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    @if (Auth::user()->is_ch_partner == 1)
                        <div class="row">
                            <div class="col-12 col-mg-4 col-lg-4">
                                <div class="x_panel bg-success text-white">
                                    <div>
                                        <p class="h4">ສ່ວນແບ່ງ</p>
                                    </div>
                                    <hr class="border-white">
                                    <div class="x_content">
                                        <p class="h2">{{ number_format($sum_share) }} ກີບ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="x_panel">
                                <div>
                                    <h2 class="card-title ">ລາຍງານຍອດການຂາຍປະຈຳສາຂາ</h2>
                                </div>
                                <div class="x_content">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class=" text-primary">
                                                <th>
                                                    ລ/ດ
                                                </th>
                                                <th>
                                                    ລະຫັດສາຂາ
                                                </th>
                                                <th>
                                                    ຊື່ສາຂາ
                                                </th>
                                                <th>
                                                    ຈຳນວນຂາຍໄດ້ (ລາຍການ)
                                                </th>
                                                <th>
                                                    ນ້ຳໜັກຂາຍໄດ້ (kg)
                                                </th>
                                                <th>
                                                    ນ້ຳໜັກຂາຍໄດ້ (ແມັດກ້ອນ)
                                                </th>
                                                <th>
                                                    ລວມເປັນເງິນ
                                                </th>
                                                <th>
                                                    ຍັງບໍ່ຈ່າຍ
                                                </th>
                                                <th>
                                                    ຈ່າຍແລ້ວ
                                                </th>
                                            </thead>
                                            <tbody>
                                                @foreach ($branch_sale_totals as $key => $branch_sale_total)
                                                    <tr>
                                                        <td>
                                                            {{ $key + 1 }}
                                                        </td>
                                                        <td>
                                                            {{ $branch_sale_total->receiver_branch_id }}
                                                        </td>
                                                        <td>
                                                            {{ $branch_sale_total->branch_name }}
                                                        </td>
                                                        <td>
                                                            @foreach ($import_product_count as $item)
                                                                @if ($item->receiver_branch_id == $branch_sale_total->receiver_branch_id)
                                                                    {{ $item->count_import_product }}
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            @foreach ($result_weight as $item)
                                                                @if ($item->receiver_branch_id == $branch_sale_total->receiver_branch_id)
                                                                    {{ $item->sum_weight_kg }}
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            @foreach ($result_weight_m as $item)
                                                                @if ($item->receiver_branch_id == $branch_sale_total->receiver_branch_id)
                                                                    {{ $item->sum_weight_m }}
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            {{ number_format($branch_sale_total->branch_total_price) }}
                                                            ກີບ
                                                        </td>
                                                        <td>
                                                            @foreach ($result_unpaid as $item)
                                                                @if ($item->receiver_branch_id == $branch_sale_total->receiver_branch_id)
                                                                    {{ number_format($item->branch_total_price) }} ກີບ
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            @foreach ($result_paid as $item)
                                                                @if ($item->receiver_branch_id == $branch_sale_total->receiver_branch_id)
                                                                    {{ number_format($item->branch_total_price) }} ກີບ
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item {{ $pagination['offset'] == 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $pagination['offset'] - 1 }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                            <li class="page-item {{ $pagination['offset'] == '1' ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page=1">1</a>
                            </li>
                            @for ($j = $pagination['offset'] - 25; $j < $pagination['offset'] - 10; $j++)
                                @if ($j % 10 == 0 && $j > 1)
                                    <li
                                        class="page-item
                                {{ $pagination['offset'] == $j ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $j }}">{{ $j }}</a>
                                    </li>
                                @else
                                @endif
                            @endfor
                            @for ($i = $pagination['offset'] - 4; $i <= $pagination['offset'] + 4 && $i <= $pagination['offsets']; $i++)
                                @if ($i > 1 && $i <= $pagination['all'])
                                    <li class="page-item {{ $pagination['offset'] == $i ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $i }}">{{ $i }}</a>
                                    </li>
                                @else

                                @endif
                            @endfor
                            @for ($j = $pagination['offset'] + 5; $j <= $pagination['offset'] + 20 && $j <= $pagination['offsets']; $j++)
                                @if ($j % 10 == 0 && $j > 1)
                                    <li
                                        class="page-item
                                {{ $pagination['offset'] == $j ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $j }}">{{ $j }}</a>
                                    </li>
                                @else
                                @endif
                            @endfor
                            <li
                                class="page-item {{ $pagination['offset'] == $pagination['offsets'] ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $pagination['offset'] + 1 }}"
                                    aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>ລາຍງານຍອດການຂາຍປະຈຳສາຂາ (ຈຳນວນຂາຍໄດ້)</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <canvas id="branch_chart_count_item"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>ລາຍງານຍອດການຂາຍປະຈຳສາຂາ (Kg)</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <canvas id="branch_chart_kg"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>ລາຍງານຍອດການຂາຍປະຈຳສາຂາ (ແມັດກ້ອນ)</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <canvas id="branch_chart_m"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>ລາຍງານຍອດການຂາຍປະຈຳສາຂາ (ລວມເປັນເງິນ)</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <canvas id="branch_chart_total_price"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        </div>
        <!-- jQuery -->
        <script src="../vendors/jquery/dist/jquery.min.js"></script>
        <!-- Chart.js -->
        <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
        <script>
            if ($('#branch_chart_kg').length) {

                var ctx = document.getElementById("branch_chart_kg");
                var branch_chart_kg = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($branch_sale_totals_branch_name); ?>,
                        datasets: [{
                            label: 'ນ້ຳໜັກ',
                            backgroundColor: "#26B99A",
                            data: <?php echo json_encode($branch_sale_totals_kg); ?>
                        }, ],
                    },

                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });

            }

            if ($('#branch_chart_m').length) {

                var ctx = document.getElementById("branch_chart_m");
                var branch_chart_m = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($branch_sale_totals_branch_name); ?>,
                        datasets: [{
                            label: 'ນ້ຳໜັກ',
                            backgroundColor: "#03586A",
                            data: <?php echo json_encode($branch_sale_totals_m); ?>
                        }],
                    },

                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });

            }


            if ($('#branch_chart_count_item').length) {

                var ctx = document.getElementById("branch_chart_count_item");
                var branch_chart_count_item = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($branch_sale_totals_branch_name); ?>,
                        datasets: [{
                            label: 'ຈຳນວນຂາຍໄດ້',
                            backgroundColor: "#0100F0",
                            data: <?php echo json_encode($import_product_count_for_chart); ?>
                        }],
                    },

                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });

            }

            if ($('#branch_chart_total_price').length) {

                var ctx = document.getElementById("branch_chart_total_price");
                var branch_chart_total_price = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: {
                        labels: <?php echo json_encode($branch_sale_totals_branch_name); ?>,
                        datasets: [{
                                label: 'ລວມເປັນເງິນ (ກີບ)',
                                backgroundColor: "#009FF0",
                                data: <?php echo json_encode($branch_sale_totals_price); ?>
                            },
                            {
                                label: 'ຍັງບໍ່ຈ່າຍ (ກີບ)',
                                backgroundColor: "#F01404",
                                data: <?php echo json_encode($branch_sale_totals_unpaid); ?>
                            },
                            {
                                label: 'ຈ່າຍແລ້ວ (ກີບ)',
                                backgroundColor: "#009C12",
                                data: <?php echo json_encode($branch_sale_totals_paid); ?>
                            }
                        ],
                    },

                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });

            }
        </script>
    @endsection
