@extends('layout')

@section('body')
    <!-- End Navbar -->
    <div class="content">
        <div class="container-fluid">

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
            @endif

            @if (Auth::user()->is_admin != 1)
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h5 class="card-title">ເລຶອກບ່ອນສົ່ງ</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="/addProduct">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ແຂວງ</label>
                                                <select class="form-control" id="select_province" required>
                                                    <option value="">
                                                        ເລືອກ
                                                    </option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}">
                                                            {{ $province->prov_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ເມືອງ</label>
                                                <select class="form-control" disabled id="select_district" required>
                                                    <option value="">
                                                        ເລືອກ
                                                    </option>
                                                    @foreach ($districts as $district)
                                                        <option value="{{ $district->id }}">
                                                            {{ $district->dist_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ສາຂາ</label>
                                                <select class="form-control" disabled id="select_branch"
                                                    name="receiver_branch_id" required>
                                                    <option value="">
                                                        ເລືອກ
                                                    </option>
                                                    @foreach ($branchs as $branch)
                                                        <option value="{{ $branch->id }}">
                                                            {{ $branch->branch_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ຂະໜາດ/ນ້ຳໜັກ</label>
                                                <input type="number" value=1 min="1" class="form-control" name="weight">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ຫົວໜ່ວຍ</label>
                                                <select class="form-control" name="weight_type" required>
                                                    <option value="">ເລືອກ</option>
                                                    <option value="gram">ກຼາມ</option>
                                                    <option value="m">ແມັດກ້ອນ</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ຊື່ລູກຄ້າຜູ້ສົ່ງ</label>
                                                <input class="form-control" name="cust_send_name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ເບີໂທລູກຄ້າຜູ້ສົ່ງ</label>
                                                <input class="form-control" name="cust_send_tel">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ຊື່ລູກຄ້າຜູ້ຮັບ</label>
                                                <input class="form-control" name="cust_receiver_name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ເບີໂທຜູ້ຮັບ</label>
                                                <input class="form-control" name="cust_receiver_tel">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary pull-right px-5">ບັນທຶກ</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h5 class="card-title">ຄົ້ນຫາ</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="/send">
                                {{-- @csrf --}}
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ລະຫັດເຄື່ອງ</label>
                                            <input class="form-control" value="{{ Request::input('id') }}" name="id">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ສະຖານະ</label>
                                            <select class="form-control" id="select_status" name="status">
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
                                                <option {{ Request::input('status') == 'success' ? 'selected' : '' }}
                                                    value="success">
                                                    ສຳເລັດ
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ສົ່ງໄປສາຂາ</label>
                                            <select class="form-control" id="select_branch" name="receive_branch">
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
                                            <input class="form-control" type="date"
                                                value="{{ Request::input('send_date') }}" name="send_date">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary pull-right px-5">ຄົ້ນຫາ</button>
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
                                            ລະຫັດເຄື່ອງ
                                        </th>
                                        <th>
                                            ຂະໜາດ
                                        </th>
                                        @if (Auth::user()->is_admin == 1)
                                            <th>
                                                ຈາກສາຂາ
                                            </th>
                                        @endif
                                        <th>
                                            ສ່ົ່ງໄປສາຂາ
                                        </th>
                                        <th>
                                            ສົ່ງວັນທີ
                                        </th>
                                        <th>
                                            ລາຄາ
                                        </th>
                                        <th>
                                            ລູກຄ້າຜູ້ສົ່ງ
                                        </th>
                                        <th>
                                            ເບີໂທຜູ້ສົ່ງ
                                        </th>
                                        <th>
                                            ລູກຄ້າຜູ້ຮັບ
                                        </th>
                                        <th>
                                            ເບີໂທຜູ້ຮັບ
                                        </th>
                                        <th>
                                            ສະຖານະ
                                        </th>
                                        <td>
                                        </td>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $key => $product)
                                            <tr>
                                                <td>
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $product->id }}
                                                </td>
                                                <td>
                                                    {{ $product->weight }}
                                                    {{ $product->weight_type == 'm' ? 'ແມັດກ້ອນ' : 'ກິໂລກຼາມ' }}
                                                </td>
                                                @if (Auth::user()->is_admin == 1)
                                                    <td>
                                                        {{ $product->sender_branch_name }}
                                                    </td>
                                                @endif
                                                <td>
                                                    {{ $product->receiver_branch_name }}
                                                </td>
                                                <td>
                                                    {{ date('d-m-Y', strtotime($product->created_at)) }}
                                                </td>
                                                <td>
                                                    {{ $product->price }} ກີບ
                                                </td>
                                                <td>
                                                    {{ $product->cust_send_name }}
                                                </td>
                                                <td>
                                                    {{ $product->cust_send_tel }}
                                                </td>
                                                <td>
                                                    {{ $product->cust_receiver_name }}
                                                </td>
                                                <td>
                                                    {{ $product->cust_receiver_tel }}
                                                </td>
                                                <td>
                                                    {{ $product->status == 'sending' ? 'ກຳລັງສົ່ງ' : ($product->status == 'received' ? 'ຮອດແລ້ວ' : 'ສຳເລັດ') }}
                                                </td>

                                                <td>
                                                    @if (!$product->received_at && Auth::user()->is_admin != 1)
                                                        <a href="/pdf/{{ $product->id }}" target="_blank">
                                                            <i class="material-icons">print</i>
                                                        </a>
                                                    @endif
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
        var district_lists = <?php echo json_encode($districts); ?> ;;
        var branch_lists = <?php echo json_encode($branchs); ?> ;;
        $("#select_province").on("change", function() {
            let province_id = this.value;
            let district_options = "<option value=''>ເລືອກ</option>";
            district_lists
                .filter(district => district.prov_id === province_id)
                .forEach(district => {
                    district_options +=
                        `<option value="${district.id}">${district.dist_name}</option>`
                });
            $("#select_district").html(district_options)
            $("#select_district").attr("disabled", false);
            $("#select_branch").val("");
            $("#select_branch").attr("disabled", true);
        });

        $("#select_district").on("change", function() {
            let district_id = this.value;
            let branch_options = "<option value=''>ເລືອກ</option>";
            branch_lists
                .filter(branch => branch.district_id === district_id)
                .forEach(branch => {
                    branch_options +=
                        `<option value="${branch.id}">${branch.branch_name}</option>`
                });
            $("#select_branch").html(branch_options)
            $("#select_branch").attr("disabled", false);
        });

        $(document).ready(function() {
            var product_id =
                "<?php echo session()->get('id') ? session()->get('id') : 'no_id'; ?>";

            if (product_id != 'no_id') {
                window.open(`pdf/${product_id}`);
            }
        });

    </script>
@endsection
