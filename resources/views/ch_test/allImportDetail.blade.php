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
            <div class="modal fade" id="new_price_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST" action="/deleteImportItemCh">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">ໃສ່ນ້ຳໜັກກ່ອນລົບ</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ນ້ຳໜັກ/ຂະໜາດ</label>
                                            <input type="hidden" id="lot_item_id" name="lot_item_id">
                                            <input type="hidden" id="lot_id" name="lot_id">
                                            <input type="hidden" id="real_price" name="real_price">
                                            <input type="hidden" id="base_price" name="base_price">
                                            <input type="hidden" id="weight_type" name="weight_type">
                                            <input type="number" id="weight" class="form-control" name="weight" step="0.001"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">ລົບຂໍ້ມູນ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="new_weight_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST" action="/changeImportItemWeightCh">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">ແກ້ໄຂນ້ຳໜັກ</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ຂະໜາດ</label>
                                            <input type="hidden" id="lot_item_id_in_weight" name="lot_item_id_in_weight">
                                            <input type="hidden" id="lot_id_in_weight" name="lot_id_in_weight">
                                            <input type="hidden" id="real_price_in_weight" name="real_price_in_weight">
                                            <input type="hidden" id="base_price_in_weight" name="base_price_in_weight">
                                            <input type="hidden" id="base_price_in_weight" name="old_weight_in_weight">
                                            <input type="number" id="weight_in_weight" step="0.001" class="form-control"
                                                name="weight_in_weight" required>
                                        </div>
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



            <div class="row">
                <div class="col">
                    <div class="x_panel">
                        <div>
                            <h2>ຄົ້ນຫາ</h2>
                        </div>
                        <div class="x_content">
                            <form method="GET" action="/importProductTrackCh">
                                {{-- @csrf --}}
                                <input type="hidden" value="{{ Request::input('id') }}" name="id">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ລະຫັດເຄື່ອງ</label>
                                            <input class="form-control form-control-sm"
                                                value="{{ Request::input('product_id') }}" name="product_id">
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
                                            <label class="bmd-label-floating">ວັນທີຮັບ</label>
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

            <div class="clearfix"></div>
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
                                            ຮັບມາວັນທີ່
                                        </th>
                                        <th>
                                            ສົ່ງໄປສາຂາ
                                        </th>
                                        <th>
                                            ຂາຍວັນທີ່
                                        </th>
                                        <th>
                                            ສະຖານະ
                                        </th>
                                        <th>
                                            ນ້ຳໜັກ/ຂະໜາດ
                                        </th>
                                        {{-- <th>
                                            ຕົ້ນທຶນ
                                        </th> --}}
                                        <th>
                                            ລາຄາຂາຍ
                                        </th>
                                        <th>

                                        </th>
                                    </thead>
                                    <tbody>
                                        @foreach ($import_products as $key => $import_product)
                                            <tr>
                                                <td>
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $import_product->code }}
                                                </td>
                                                <td>
                                                    {{ $import_product->created_at ? date('d-m-Y', strtotime($import_product->created_at)) : '' }}
                                                </td>
                                                <td>
                                                    {{ $import_product->branch_name }}
                                                </td>
                                                <td>
                                                    {{ $import_product->success_at }}
                                                </td>
                                                <td>
                                                    {{ $import_product->status == 'sending' ? 'ກຳລັງສົ່ງ' : ($import_product->status == 'received' ? 'ຮອດແລ້ວ' : ($import_product->status == 'waiting' ? 'ລໍຖ້າ' : 'ສຳເລັດ')) }}
                                                </td>
                                                <td>
                                                    {{ $import_product->weight }}
                                                    {{ $import_product->weight_type == 'm' ? 'ແມັດກ້ອນ' : 'ກິໂລກຼາມ' }}
                                                </td>
                                                {{-- <td>
                                                    {{ number_format($import_product->total_real_price) }}
                                                </td> --}}
                                                <td>
                                                    {{ number_format($import_product->total_sale_price) }}
                                                </td>
                                                <td>
                                                    @if ($import_product->status != 'success' && Auth::user()->is_owner == 1)
                                                        @if ($import_product->status != 'success')
                                                            <a type="button"
                                                                onclick="change_price({{ $import_product->id . ',' . $import_product->lot_id . ',' . $import_product->base_price . ',' . $import_product->real_price . ',' . $import_product->weight . ',' }}'{{ $import_product->weight_type }}')"
                                                                data-toggle="modal" data-target="#new_price_modal">
                                                                <i class="material-icons">delete_forever</i>
                                                            </a>
                                                        @endif
                                                        @if ($import_product->weight_type == 'm' && $import_product->status != 'success')
                                                            <a type="button"
                                                                onclick="change_weight({{ $import_product->id . ',' . $import_product->lot_id . ',' . $import_product->base_price . ',' . $import_product->real_price . ',' . $import_product->weight }})"
                                                                data-toggle="modal" data-target="#new_weight_modal">
                                                                <i class="material-icons">create</i>
                                                            </a>
                                                        @endif
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
                        @if ($j % 10 == 0 && $j > 1) <li class="page-item
                        {{ $pagination['offset'] == $j ? 'active' : '' }}">
                        <a class="page-link"
                        href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page={{ $j }}">{{ $j }}</a>
                        </li>
                    @else @endif
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
                        @if ($j % 10 == 0 && $j > 1) <li class="page-item
                        {{ $pagination['offset'] == $j ? 'active' : '' }}">
                        <a class="page-link"
                        href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page={{ $j }}">{{ $j }}</a>
                        </li>
                    @else @endif
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
        function change_price(id, lot_id, base_price, real_price, weight, weight_type) {
            $("#lot_item_id").val(id);
            $("#lot_id").val(lot_id);
            $("#base_price").val(base_price);
            $("#real_price").val(real_price);
            $("#weight").val(weight);
            $("#weight_type").val(weight_type);
        }

        function change_weight(id, lot_id, base_price, real_price, old_weight) {
            $("#lot_item_id_in_weight").val(id);
            $("#lot_id_in_weight").val(lot_id);
            $("#base_price_in_weight").val(base_price);
            $("#real_price_in_weight").val(real_price);
            $("#old_weight_in_weight").val(old_weight);
            $("#weight_in_weight").val(old_weight);
        }

    </script>
@endsection
