@extends('layout')

@section('body')
<!-- End Navbar -->
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>ສົ່ງເຄື່ອງໃຫ້ລູກຄ້າ</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        @isset($update_success)
        @if ($update_success)
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
            </button>
            <span>
                <b> Success - </b>ບັນທຶກຂໍ້ມູນສຳເລັດ</span>
        </div>
        @else
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
            </button>
            <span>
                <b> Danger - </b>ເກີດຂໍ້ຜິດພາດ ກະລຸນາລອງໃໝ່</span>
        </div>
        @endif
        @endisset

        <div class="row">
            <div class="col">
                <div class="x_panel">
                    <div>
                        <h2>ສົ່ງເຄື່ອງໃຫ້ລູກຄ້າ</h2>
                    </div>
                    <div class="x_content">
                        <form method="POST" action="/successProduct">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ລະຫັດເຄື່ອງ</label>
                                        <input class="form-control" id="input_id" name="id">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary px-5">ບັນທຶກ</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="x_panel">
                    <div>
                        <h2>ຄົ້ນຫາ</h2>
                    </div>
                    <div class="x_content">
                        <form method="GET" action="/success">
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
                                        <label class="bmd-label-floating">ຈາກສາຂາ</label>
                                        <select class="form-control" id="select_branch" name="send_branch">
                                            <option value="">
                                                ເລືອກ
                                            </option>
                                            @foreach ($branchs as $branch)
                                            <option {{ Request::input('send_branch')==$branch->id ? 'selected' : '' }}
                                                value="{{ $branch->id }}">
                                                {{ $branch->branch_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ສະຖານະ</label>
                                        <select class="form-control" id="select_status" name="status">
                                            <option value="">
                                                ເລືອກ
                                            </option>
                                            <option {{ Request::input('status')=='sending' ? 'selected' : '' }}
                                                value="sending">
                                                ກຳລັງສົ່ງ
                                            </option>
                                            <option {{ Request::input('status')=='received' ? 'selected' : '' }}
                                                value="received">
                                                ຮອດແລ້ວ
                                            </option>
                                            <option {{ Request::input('status')=='success' ? 'selected' : '' }}
                                                value="success">
                                                ສຳເລັດ
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ວັນທີຮັບ</label>
                                        <input class="form-control" type="date"
                                            value="{{ Request::input('receive_date') }}" name="receive_date">
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
                <div class="x_panel">
                    <div>
                        <h2 class="card-title ">ລາຍການເຄື່ອງເຂົ້າທັງໝົດຂອງສາຂາ</h2>
                    </div>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                    <th>
                                        ລະຫັດເຄື່ອງ
                                    </th>
                                    <th>
                                        ຂະໜາດ
                                    </th>
                                    <th>
                                        ຈາກສາຂາ
                                    </th>
                                    <th>
                                        ສົ່ງວັນທີ
                                    </th>
                                    <th>
                                        ລູກຄ້າຜູ້ສົ່ງ
                                    </th>
                                    <th>
                                        ລູກຄ້າຜູ້ຮັບ
                                    </th>
                                    <th>
                                        ເບີໂທ
                                    </th>
                                    <th>
                                        ສະຖານະ
                                    </th>
                                    <th>
                                        ລາຄາ
                                    </th>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            {{ $product->id }}
                                        </td>
                                        <td>
                                            {{ $product->weight }} {{ $product->weight_type }}
                                        </td>
                                        <td>
                                            {{ $product->branch_name }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($product->created_at)) }}
                                        </td>
                                        <td>
                                            {{ $product->cust_send_name }}
                                        </td>
                                        <td>
                                            {{ $product->cust_receiver_name }}
                                        </td>
                                        <td>
                                            {{ $product->cust_receiver_tel }}
                                        </td>
                                        <td>
                                            {{ $product->status == 'sending' ? 'ກຳລັງສົ່ງ' : ($product->status ==
                                            'received' ? 'ຮອດແລ້ວ' : 'ສຳເລັດ') }}
                                        </td>
                                        <td>
                                            {{ $product->price }} ກີບ
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
                        href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&send_branch={{ Request::input('send_branch') }}&receive_date={{ Request::input('receive_date') }}&page={{ $pagination['offset'] - 1 }}"
                        aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <li class="page-item {{ $pagination['offset'] == '1' ? 'active' : '' }}">
                    <a class="page-link"
                        href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&send_branch={{ Request::input('send_branch') }}&receive_date={{ Request::input('receive_date') }}&page=1">1</a>
                </li>
                @for ($j = $pagination['offset'] - 25; $j < $pagination['offset'] - 10; $j++) @if ($j % 10==0 && $j> 1)
                    <li class="page-item {{ $pagination['offset'] == $j ? 'active' : '' }}">
                        <a class="page-link"
                            href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&send_branch={{ Request::input('send_branch') }}&receive_date={{ Request::input('receive_date') }}&page={{ $j }}">{{
                            $j }}</a>
                    </li>
                    @else

                    @endif
                    @endfor
                    @for ($i = $pagination['offset'] - 4; $i <= $pagination['offset'] + 4 && $i
                        <=$pagination['offsets']; $i++) @if ($i> 1 && $i <= $pagination['all']) <li
                            class="page-item {{ $pagination['offset'] == $i ? 'active' : '' }}">
                            <a class="page-link"
                                href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&send_branch={{ Request::input('send_branch') }}&receive_date={{ Request::input('receive_date') }}&page={{ $i }}">{{
                                $i }}</a>
                            </li>
                            @else

                            @endif
                            @endfor
                            @for ($j = $pagination['offset'] + 5; $j <= $pagination['offset'] + 20 && $j
                                <=$pagination['offsets']; $j++) @if ($j % 10==0 && $j> 1)
                                <li class="page-item {{ $pagination['offset'] == $j ? 'active' : '' }}">
                                    <a class="page-link"
                                        href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&send_branch={{ Request::input('send_branch') }}&receive_date={{ Request::input('receive_date') }}&page={{ $j }}">{{
                                        $j }}</a>
                                </li>
                                @else

                                @endif
                                @endfor
                                <li
                                    class="page-item {{ $pagination['offset'] == $pagination['offsets'] ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&send_branch={{ Request::input('send_branch') }}&receive_date={{ Request::input('receive_date') }}&page={{ $pagination['offset'] + 1 }}"
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
    $(document).ready(function () {
        $("#input_id").focus();
    })

</script>
@endsection