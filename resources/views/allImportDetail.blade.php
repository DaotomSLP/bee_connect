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
                    <form method="POST" action="/deleteImportItem">
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
                    <form method="POST" action="/changeImportItemWeight">
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
                            <form method="GET" action="/importProductTrackForUser">
                                {{-- @csrf --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ລະຫັດເຄື່ອງ</label>
                                            <input class="form-control form-control-sm"
                                                value="{{ Request::input('product_id') }}" name="product_id">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
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
                                                    {{ $import_product->status == 'sending' ? 'ກຳລັງສົ່ງ' : ($import_product->status == 'received' ? 'ຮອດແລ້ວ' : 'ສຳເລັດ') }}
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
                                                    @if ($import_product->status != 'success')
                                                        @if ($import_product->status != 'success')
                                                            <a type="button"
                                                                onclick="change_price({{ $import_product->id . ',' . $import_product->lot_id . ',' . $import_product->base_price . ',' . $import_product->real_price . ',' . $import_product->weight }})"
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
        var district_lists = < ? php echo json_encode($districts); ?> ;;
        var branch_lists = < ? php echo json_encode($branchs); ?> ;;
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
                window.open(`importpdf/${product_id}`);
            }
        });

        var codes = [];
        $('#product_id').keypress(function(event) {
            if (event.keyCode == 13) {
                let code = $('#product_id').val();
                if (code == '') {
                    alert("empty!!!");
                } else {
                    if (codes.includes(code)) {
                        alert("ລະຫັດຊ້ຳ");
                    } else {
                        generateItem(code);
                        codes.push(code);
                        $('#product_id').val('');
                    }
                }
            }
        });

        function generateItem(code) {
            $('#product_item_table').append(
                `<tr><td class="py-0"><div class="form-group"><input value='${code}' class="form-control form-control-sm" name="item_id[]" required></div></td><td class="py-0"><div class="form-group"><input type="number" value=0 min="0"class="form-control form-control-sm" name="weight[]" required></div></td><td class="py-0"><div class="form-group"><select class="form-control form-control-sm" name="weight_type[]"required><option value="gram">ກິໂລກຼາມ</option> <option value="m">ແມັດກ້ອນ</option></select></div></td><td class="py-0"><div class="form-group"><input class="form-control form-control-sm" name="base_price[]"> </div></td> <td class="py-0"><div class="form-group"><input class="form-control form-control-sm" name="real_price[]"></div></td></tr>`
            )
        }

    </script>
@endsection
