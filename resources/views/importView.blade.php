@extends('layout')

@section('body')
    <!-- End Navbar -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h5 class="card-title">ຄົ້ນຫາ</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="/import">
                                {{-- @csrf --}}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ເລກບິນ</label>
                                            <input class="form-control form-control-sm" value="{{ Request::input('id') }}"
                                                name="id">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ສະຖານະ</label>
                                            <select class="form-control form-control-sm" id="select_status" name="status">
                                                <option value="">
                                                    ເລືອກ
                                                </option>
                                                <option {{ Request::input('status') == 'sending' ? 'selected' : '' }}
                                                    value="sending">
                                                    ກຳລັງສົ່ງ
                                                </option>
                                                <option {{ Request::input('status') == 'received' ? 'selected' : '' }}
                                                    value="received">
                                                    ຮອດແລ້ວ
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ສະຖານະການຈ່າຍເງິນ</label>
                                            <select class="form-control form-control-sm" id="select_status"
                                                name="payment_status">
                                                <option value="">
                                                    ເລືອກ
                                                </option>
                                                <option
                                                    {{ Request::input('payment_status') == 'not_paid' ? 'selected' : '' }}
                                                    value="not_paid">
                                                    ຍັງບໍ່ຈ່າຍ
                                                </option>
                                                <option {{ Request::input('payment_status') == 'paid' ? 'selected' : '' }}
                                                    value="paid">
                                                    ຈ່າຍແລ້ວ
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ສົ່ງໄປສາຂາ</label>
                                            <select class="form-control form-control-sm" id="select_branch"
                                                name="receive_branch">
                                                <option value="">
                                                    ເລືອກ
                                                </option>
                                                @foreach ($branchs as $branch)
                                                    <option
                                                        {{ Request::input('receive_branch') == $branch->id ? 'selected' : '' }}
                                                        value="{{ $branch->id }}">
                                                        {{ $branch->branch_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ວັນທີສົ່ງ</label>
                                            <input class="form-control form-control-sm" type="date"
                                                value="{{ Request::input('send_date') }}" name="send_date">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary pull-right px-4">ຄົ້ນຫາ</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h5 class="card-title ">ລາຍການສົ່ງອອກທັງໝົດຂອງສາຂາ</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class=" text-primary">
                                        <th>
                                            ລ/ດ
                                        </th>
                                        <th>
                                            ເລກບິນ
                                        </th>
                                        @if (Auth::user()->is_admin == 1)
                                            <th>
                                                ສົ່ງໄປສາຂາ
                                            </th>
                                        @endif
                                        <th>
                                            ຮັບມາວັນທີ່
                                        </th>
                                        <th>
                                            kg
                                        </th>
                                        @if (Auth::user()->is_admin == 1)
                                            <th>
                                                ລວມຕົ້ນທຶນ
                                            </th>
                                        @endif
                                        <th>
                                            {{ Auth::user()->is_admin != 1 ? 'ຕົ້ນທຶນ' : 'ລວມລາຄາຂາຍ' }}
                                        </th>
                                        @if (Auth::user()->is_admin != 1)
                                            <th>
                                                ລວມຂາຍໄດ້
                                            </th>
                                        @endif
                                        <th>
                                            ກຳໄລ
                                        </th>
                                        <th>
                                            ສະຖານະ
                                        </th>
                                        <th>
                                            ສະຖານະຈ່າຍເງິນ
                                        </th>
                                        <th>

                                        </th>
                                        {{-- <th>

                                        </th> --}}
                                    </thead>
                                    <tbody>
                                        @foreach ($lots as $key => $lot)
                                            <tr>
                                                <td>
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $lot->id }}
                                                </td>
                                                @if (Auth::user()->is_admin == 1)
                                                    <td>
                                                        {{ $lot->receiver_branch_name }}
                                                    </td>
                                                @endif

                                                <td>
                                                    {{ date('d-m-Y', strtotime($lot->created_at)) }}
                                                </td>
                                                <td>
                                                    {{ $lot->weight_kg }}
                                                </td>
                                                @if (Auth::user()->is_admin == 1)
                                                    <td>
                                                        {{ number_format($lot->total_base_price) }} ກີບ
                                                    </td>
                                                @endif
                                                <td>
                                                    {{ number_format($lot->total_price) }} ກີບ
                                                </td>

                                                @if (Auth::user()->is_admin == 1)
                                                    <td>
                                                        {{ number_format($lot->total_price - $lot->total_base_price) }} ກີບ
                                                    </td>
                                                @endif

                                                @if (Auth::user()->is_admin != 1)
                                                    <td>
                                                        {{ number_format($lot->total_sale_price) }} ກີບ
                                                    </td>
                                                    <td>
                                                        {{ number_format($lot->total_sale_price - $lot->total_price) }} ກີບ
                                                    </td>
                                                @endif
                                                <td>
                                                    {{ $lot->status == 'sending' ? 'ກຳລັງສົ່ງ' : ($lot->status == 'received' ? 'ຄົບແລ້ວ' : ($lot->status == 'not_full' ? 'ຍັງບໍ່ຄົບ' : 'ສຳເລັດ')) }}
                                                </td>
                                                <td>
                                                    {{ $lot->payment_status == 'not_paid' ? 'ຍັງບໍ່ຈ່າຍ' : 'ຈ່າຍແລ້ວ' }}
                                                </td>
                                                <td>
                                                    <a
                                                        href="/{{ Auth::user()->is_admin == 1 ? 'importDetail' : 'importDetailForUser' }}?id={{ $lot->id }}">
                                                        ລາຍລະອຽດ
                                                    </a>
                                                    @if ($lot->status != 'success' && Auth::user()->is_admin == 1)

                                                        <a href="/deleteLot?id={{ $lot->id }}">
                                                            <i class="material-icons">delete_forever</i>
                                                        </a>

                                                    @endif
                                                    @if ($lot->payment_status == 'not_paid' && Auth::user()->is_admin == 1)

                                                        <a href="/paidLot?id={{ $lot->id }}">
                                                            ຈ່າຍແລ້ວ
                                                        </a>

                                                    @endif
                                                </td>
                                                {{-- <td>
                                                    @if (!$lot->received_at)
                                                        <a href="/importpdf/{{ $lot->id }}" target="_blank">
                                                            <i class="material-icons">print</i>
                                                        </a>
                                                    @endif
                                                </td> --}}
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
                            href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page={{ $pagination['offset'] - 1 }}"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item {{ $pagination['offset'] == '1' ? 'active' : '' }}">
                        <a class="page-link"
                            href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page=1">1</a>
                    </li>
                    @for ($j = $pagination['offset'] - 25; $j < $pagination['offset'] - 10; $j++)
                        @if ($j % 10 == 0 && $j > 1)
                            <li class="page-item {{ $pagination['offset'] == $j ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else

                        @endif
                    @endfor
                    @for ($i = $pagination['offset'] - 4; $i <= $pagination['offset'] + 4 && $i <= $pagination['offsets']; $i++)
                        @if ($i > 1 && $i <= $pagination['all'])
                            <li class="page-item {{ $pagination['offset'] == $i ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page={{ $i }}">{{ $i }}</a>
                            </li>
                        @else

                        @endif
                    @endfor
                    @for ($j = $pagination['offset'] + 5; $j <= $pagination['offset'] + 20 && $j <= $pagination['offsets']; $j++)
                        @if ($j % 10 == 0 && $j > 1)
                            <li class="page-item {{ $pagination['offset'] == $j ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else

                        @endif
                    @endfor
                    <li class="page-item {{ $pagination['offset'] == $pagination['offsets'] ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page={{ $pagination['offset'] + 1 }}"
                            aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>

    </script>
@endsection
