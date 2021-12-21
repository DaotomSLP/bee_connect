@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>ການສົ່ງສິນຄ້າພາຍໃນ</h3>
                </div>
            </div>
            <div class="clearfix"></div>

            <!-- Modal -->
            <div class="modal fade" id="new_weight_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST" action="/changeImportWeight">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="modal-title" id="exampleModalLabel">ແກ້ໄຂນ້ຳໜັກລວມ</h2>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ນ້ຳໜັກລວມ</label>
                                            <input type="hidden" id="lot_id_in_weight" name="lot_id_in_weight">
                                            <input type="hidden" id="fee" name="fee">
                                            <input type="hidden" id="pack_price" name="pack_price">
                                            <input type="number" id="weight_in_weight" class="form-control"
                                                name="weight_in_weight" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label class="bmd-label-floating">ລາຄາຕົ້ນທຶນ (kg)</label>
                                        <input type="number" class="form-control" id="lot_base_price_kg"
                                            name="lot_base_price_kg" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label class="bmd-label-floating">ລາຄາຂາຍ (kg)</label>
                                        <input type="number" class="form-control" id="lot_real_price_kg"
                                            name="lot_real_price_kg" required>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col">
                                        <label class="bmd-label-floating">ລາຄາຕົ້ນທຶນ (ແມັດກ້ອນ)</label>
                                        <input type="number" class="form-control" id="lot_base_price_m"
                                            name="lot_base_price_m" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label class="bmd-label-floating">ລາຄາຂາຍ (ແມັດກ້ອນ)</label>
                                        <input type="number" class="form-control" id="lot_real_price_m"
                                            name="lot_real_price_m" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">ບັນທຶກ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="delete_lot_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="GET" action="/deleteLot">
                        @csrf
                        <div class="modal-content">
                            <div>
                                <h2 class="text-center" id="exampleModalLabel"><i
                                        class="material-icons h1">delete_forever</i><br>ຕ້ອງການລົບລາຍການນີ້ ຫຼືບໍ່?</h2>
                            </div>

                            <input type="hidden" id="lot_id_input" name="id">

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">ຕົກລົງ</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"
                                    aria-label="Close">ຍົກເລີກ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="paid_lot_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="GET" action="/paidLot">
                        @csrf
                        <div class="modal-content">
                            <div>
                                <h2 class="text-center" id="exampleModalLabel"><i
                                        class="material-icons h1">paid</i><br>ຕ້ອງການຈ່າຍເງິນໃຫ້ກັບລາຍການນີ້ ຫຼືບໍ່?</h2>
                            </div>

                            <input type="hidden" id="paid_lot_id_input" name="id">

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">ຕົກລົງ</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"
                                    aria-label="Close">ຍົກເລີກ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if (session()->get('error') == 'not_insert')
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                    <span>
                        <b> Danger - </b>ເກີດຂໍ້ຜິດພາດ ກະລຸນາລອງໃໝ່</span>
                </div>
            @elseif(session()->get( 'error' )=='insert_success')
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                    <span>
                        <b> Success - </b>ບັນທຶກຂໍ້ມູນສຳເລັດ</span>
                </div>
            @elseif(session()->get( 'error' )=='delete_success')
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                    <span>
                        <b> Success - </b>ລົບຂໍ້ມູນສຳເລັດ</span>
                </div>
            @endif


            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title">ຄົ້ນຫາ</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="/importView">
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

                                    @if (Auth::user()->is_admin == 1)
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
                                    @endif

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ວັນທີສົ່ງ</label>
                                            <input class="form-control form-control-sm" type="date"
                                                value="{{ Request::input('send_date') }}" name="send_date">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm btn-info pull-right px-4">ຄົ້ນຫາ</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title">ລາຍການສົ່ງອອກທັງໝົດຂອງສາຂາ</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="font-weight-bold">
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
                                        <th>
                                            m
                                        </th>
                                        {{-- @if (Auth::user()->is_admin == 1)
                                            <th>
                                                ລວມຕົ້ນທຶນ
                                            </th>
                                            <th>
                                                ລວມລາຄາເຄື່ອງ
                                            </th>
                                        @endif --}}
                                        @if (Auth::user()->is_admin != 1)
                                            <th>
                                                ລວມຕົ້ນທຶນຄ່າເຄື່ຶອງ
                                            </th>
                                            <th>
                                                ຄ່າຂົນສົ່ງ
                                            </th>
                                            <th>
                                                ຄ່າເປົາ
                                            </th>
                                            <th>
                                                ຄ່າບໍລິການເພີ່ມເຕີມ
                                            </th>
                                        @endif

                                        @if (Auth::user()->is_admin == 1)
                                            <th>
                                                ລວມເປັນເງິນທັງໝົດ
                                            </th>
                                        @endif
                                        @if (Auth::user()->is_admin != 1)
                                            <th>
                                                ລວມຕົ້ນທຶນທັງໝົດ
                                            </th>
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
                                        <th>

                                        </th>
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
                                                <td>
                                                    {{ $lot->weight_m }}
                                                </td>
                                                {{-- @if (Auth::user()->is_admin == 1)
                                                    <td>
                                                        {{ number_format($lot->total_base_price) }} ກີບ
                                                    </td>
                                                @endif --}}
                                                <td>
                                                    {{ number_format($lot->total_price) }} ກີບ
                                                </td>
                                                @if (Auth::user()->is_admin != 1)
                                                    <td>
                                                        {{ number_format($lot->fee) }} ກີບ
                                                    </td>
                                                    <td>
                                                        {{ number_format($lot->pack_price) }} ກີບ
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="/{{ Auth::user()->is_admin == 1 ? 'serviceChargeDetail' : 'serviceChargeDetailForUser' }}?id={{ $lot->id }}">
                                                            {{ number_format($lot->service_charge) }} ກີບ
                                                        </a>
                                                    </td>
                                                @endif
                                                <td>
                                                    <p class="text-danger font-weight-bold">
                                                        {{ number_format($lot->total_main_price) }} ກີບ</p>
                                                </td>
                                                @if (Auth::user()->is_admin == 1)
                                                    <td>
                                                        {{ number_format($lot->total_price - $lot->total_base_price) }}
                                                        ກີບ
                                                    </td>
                                                @endif

                                                @if (Auth::user()->is_admin != 1)
                                                    <td>
                                                        {{ number_format($lot->total_sale_price) }} ກີບ
                                                    </td>
                                                    <td>
                                                        {{ number_format($lot->total_sale_price - $lot->total_price) }}
                                                        ກີບ
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
                                                        <i class="material-icons">assignment</i>
                                                    </a>
                                                    @if ($lot->status != 'success' && Auth::user()->is_owner == 1)

                                                        <a type="button" onclick="deleteLot({{ $lot->id }})"
                                                            data-toggle="modal" data-target="#delete_lot_modal">
                                                            <i class="material-icons">delete_forever</i>
                                                        </a>

                                                    @endif
                                                    @if ($lot->status != 'success' && Auth::user()->is_owner == 1)
                                                        <a type="button"
                                                            onclick="change_weight({{ $lot->id . ',' . ($lot->lot_base_price_kg ? $lot->lot_base_price_kg : 0) . ',' . ($lot->lot_real_price_kg ? $lot->lot_real_price_kg : 0) . ',' . ($lot->lot_base_price_m ? $lot->lot_base_price_m : 0) . ',' . ($lot->lot_real_price_m ? $lot->lot_real_price_m : 0) . ',' . ($lot->weight_kg ? $lot->weight_kg : 0) . ',' . ($lot->fee ? $lot->fee : 0) . ',' . ($lot->pack_price ? $lot->pack_price : 0) }})"
                                                            data-toggle="modal" data-target="#new_weight_modal">
                                                            <i class="material-icons">create</i>
                                                        </a>
                                                    @endif
                                                    @if (Auth::user()->is_admin == 1)
                                                        <a href="/importpdf/{{ $lot->id }}" target="_blank">
                                                            <i class="material-icons">print</i>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($lot->payment_status == 'not_paid' && Auth::user()->is_admin == 1)
                                                        {{-- <a href="/paidLot?id={{ $lot->id }}"> --}}
                                                        <a type="button" class="btn btn-sm btn-info text-white"
                                                            onclick="paidLot({{ $lot->id }})" data-toggle="modal"
                                                            data-target="#paid_lot_modal">
                                                            ຈ່າຍເງິນ
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
                            <li
                                class="page-item
                        {{ $pagination['offset'] == $j ? 'active' : '' }}">
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
                            <li
                                class="page-item
                        {{ $pagination['offset'] == $j ? 'active' : '' }}">
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
        function change_weight(lot_id, base_price_kg, real_price_kg, base_price_m, real_price_m, old_weight,
            fee,
            pack_price) {
            $("#lot_id_in_weight").val(lot_id);
            $("#lot_base_price_kg").val(base_price_kg);
            $("#lot_real_price_kg").val(real_price_kg);
            $("#lot_base_price_m").val(base_price_m);
            $("#lot_real_price_m").val(real_price_m);
            $("#fee").val(fee);
            $("#pack_price").val(pack_price);
            $("#weight_in_weight").val(old_weight);
        }

        function deleteLot(id) {
            $("#lot_id_input").val(id);
        }

        function paidLot(id) {
            $("#paid_lot_id_input").val(id);
        }
    </script>
@endsection
