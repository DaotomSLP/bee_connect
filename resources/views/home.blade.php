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
                    <form method="GET" action="home">
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
            @if (Auth::user()->is_admin != 1 && Auth::user()->branch_id == null)
                <div class="row">
                    <div class="col-12 col-mg-4 col-lg-4">
                        <div class="x_panel">
                            <div>
                                <p class="h4">ຈຳນວນເງິນທີ່ໄດ້ຮັບທັງໝົດ</p>
                            </div>
                            <hr>
                            <div class="x_content">
                                <p class="h2">{{ number_format($sum_price) }} ກີບ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-mg-4 col-lg-4">
                        <div class="x_panel">
                            <div>
                                <p class="h4">ສ່ວນແບ່ງ</p>
                            </div>
                            <hr>
                            <div class="x_content">
                                {{-- <p class="h2">{{ number_format($sum_share) }} ກີບ</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            @else
                <div class="row">
                    <div class="col-12 col-mg-4 col-lg-4">
                        <div class="x_panel">
                            <div>
                                <p class="h4">ຈຳນວນເງິນທີ່ໄດ້ຮັບທັງໝົດ</p>
                            </div>
                            <hr>
                            <div class="x_content">
                                <p class="h2">{{ number_format($sum_price) }} ກີບ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-mg-4 col-lg-4">
                        <div class="x_panel">
                            <div>
                                <p class="h4">ສ່ວນແບ່ງ</p>
                            </div>
                            <hr>
                            <div class="x_content">
                                <p class="h2">{{ number_format($branch_money) }} ກີບ</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if (Auth::user()->is_admin != 1)
                    <p class="h3 pt-4">ຂົນສົ່ງຂາອອກ :</p>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel">
                                <div>
                                    <p class="h4">ລາຍການກຳລັັງຂົນສົ່ງ</p>
                                </div>
                                <hr>
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_delivery_sending) }} </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel">
                                <div>
                                    <p class="h4">ລາຍການໄດ້ຮັບແລ້ວ</p>
                                </div>
                                <hr>
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_delivery_received) }} </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel">
                                <div>
                                    <p class="h4">ລາຍການສຳເລັດແລ້ວ</p>
                                </div>
                                <hr>
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_delivery_success) }} </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="h3 pt-4">ຂົນສົ່ງຂາເຂົ້າ :</p>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel">
                                <div>
                                    <p class="h4">ລາຍການກຳລັັງຂົນສົ່ງ</p>
                                </div>
                                <hr>
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_receive_sending) }} </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel">
                                <div>
                                    <p class="h4">ລາຍການໄດ້ຮັບແລ້ວ</p>
                                </div>
                                <hr>
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_received) }} </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel">
                                <div>
                                    <p class="h4">ລາຍການສຳເລັດແລ້ວ</p>
                                </div>
                                <hr>
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_success) }} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                @else
                    <div class="row">
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel">
                                <div>
                                    <p class="h4">ລາຍການກຳລັັງຂົນສົ່ງ</p>
                                </div>
                                <hr>
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_delivery_sending) }} </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel">
                                <div>
                                    <p class="h4">ລາຍການໄດ້ຮັບແລ້ວ</p>
                                </div>
                                <hr>
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_received) }} </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel">
                                <div>
                                    <p class="h4">ລາຍການສຳເລັດແລ້ວ</p>
                                </div>
                                <hr>
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_success) }} </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-mg-4 col-lg-4">
                            <div class="x_panel">
                                <div>
                                    <p class="h4 d-inline">ລາຍຈ່າຍອື່ນໆ</p>
                                    <p class="pl-3 h4 d-inline"><a href="/expenditure"><i
                                                class="fa fa-arrow-right"></i></span></a>
                                    </p>
                                </div>
                                <hr>
                                <div class="x_content">
                                    <p class="h2">{{ number_format($sum_expenditure) }} ກີບ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <hr>

            @endif
            {{-- <div class="row">
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
                                                ຈຳນວນຂາຍໄດ້ທັງໝົດ (ລາຍການ)
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
                                                        {{ number_format($branch_sale_total->branch_total_price) }} ກີບ
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
                </nav> --}}




        </div>
    </div>
    </div>
@endsection
