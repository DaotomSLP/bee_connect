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
                    <form method="GET" action="dailyImportTh">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-12">
                                <div class="form-group">
                                    <input class="form-control" type="date" value="{{ $date_now }}" name="date">
                                </div>
                            </div>
                            <p class="h5">ຫາ</p>
                            <div class="col-lg-4 col-md-4 col-12">
                                <div class="form-group">
                                    <input class="form-control" type="date" value="{{ $to_date_now }}" name="to_date">
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
            @if (Auth::user()->is_branch == 1)
                <div class="row">
                    <div class="col-12 col-mg-4 col-lg-4">
                        <div class="x_panel">
                            <div>
                                <p class="h4">ຈຳນວນເງິນທີ່ໄດ້ຮັບ</p>
                            </div>
                            <hr>
                            <div class="x_content">
                                <p class="h2">{{ number_format($sum_real_price) }} ບາດ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-mg-4 col-lg-4">
                        <div class="x_panel">
                            <div>
                                <p class="h4">ຕົ້ນທຶນ</p>
                            </div>
                            <hr>
                            <div class="x_content">
                                <p class="h2">{{ number_format($sum_base_price) }} ບາດ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-mg-4 col-lg-4">
                        <div class="x_panel">
                            <div>
                                <p class="h4">ກຳໄລການຂາຍ</p>
                            </div>
                            <hr>
                            <div class="x_content">
                                <p class="h2">{{ number_format($sum_sale_profit) }} ບາດ</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12 col-mg-4 col-lg-4">
                        <div class="x_panel">
                            <div>
                                <p class="h4">ຈຳນວນເງິນທີ່ໄດ້ຮັບ</p>
                            </div>
                            <hr>
                            <div class="x_content">
                                <p class="h2">{{ number_format($sum_real_price) }} ບາດ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-mg-4 col-lg-4">
                        <div class="x_panel">
                            <div>
                                <p class="h4 d-inline">ຕົ້ນທຶນ</p>
                                <p class="pl-3 h4 d-inline"><a href="/base_price_th"><i
                                            class="fa fa-arrow-right"></i></span></a></p>
                            </div>
                            <hr>
                            <div class="x_content">
                                <p class="h2">{{ number_format($sum_base_price) }} ບາດ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-mg-4 col-lg-4">
                        <div class="x_panel">
                            <div>
                                <p class="h4">ກຳໄລການຂາຍ</p>
                            </div>
                            <hr>
                            <div class="x_content">
                                <p class="h2">{{ number_format($sum_sale_profit) }} ບາດ</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-mg-4 col-lg-4">
                        <div class="x_panel">
                            <div>
                                <p class="h4">ລວມຄ່າຂົນສົ່ງ</p>
                            </div>
                            <hr>
                            <div class="x_content">
                                <p class="h2">{{ number_format($sum_fee_price) }} ບາດ</p>
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
                                <p class="h2">{{ number_format($sum_pack_price) }} ບາດ</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                {{-- <div class="row">
                    <div class="col-12 col-mg-4 col-lg-4">
                        <div class="x_panel">
                            <div>
                                <p class="h4 d-inline">ລາຍຈ່າຍອື່ນໆ</p>
                                <p class="pl-3 h4 d-inline"><a href="/expenditure"><i
                                            class="fa fa-arrow-right"></i></span></a></p>
                            </div>
                            <hr>
                            <div class="x_content">
                                <p class="h2">{{ number_format($sum_expenditure) }} ບາດ</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr> --}}
                {{-- <div class="row">
                    <div class="col-12 col-mg-4 col-lg-4">
                        <div class="x_panel">
                            <div>
                                <p class="h4">ກຳໄລ</p>
                            </div>
                            <hr>
                            <div class="x_content">
                                <p class="h2">{{ number_format($sum_profit) }} ບາດ</p>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <hr>

                @if (Auth::user()->is_thai_partner == 1)
                    <div class="row">
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel">
                                <div>
                                    <p class="h4">ສ່ວນແບ່ງ</p>
                                </div>
                                <hr>
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_share) }} ບາດ</p>
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
                                                        {{ number_format($branch_sale_total->branch_total_price) }} ບາດ
                                                    </td>
                                                    <td>
                                                        @foreach ($result_unpaid as $item)
                                                            @if ($item->receiver_branch_id == $branch_sale_total->receiver_branch_id)
                                                                {{ number_format($item->branch_total_price) }} ບາດ
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($result_paid as $item)
                                                            @if ($item->receiver_branch_id == $branch_sale_total->receiver_branch_id)
                                                                {{ number_format($item->branch_total_price) }} ບາດ
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
                            @if ($j % 10 == 0 && $j > 1) <li class="page-item
                            {{ $pagination['offset'] == $j ? 'active' : '' }}">
                            <a class="page-link"
                            href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else @endif
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
                            @if ($j % 10 == 0 && $j > 1) <li class="page-item
                            {{ $pagination['offset'] == $j ? 'active' : '' }}">
                            <a class="page-link"
                            href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else @endif
                        @endfor
                        <li class="page-item {{ $pagination['offset'] == $pagination['offsets'] ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $pagination['offset'] + 1 }}"
                                aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
    </div>
    </div>
@endsection
