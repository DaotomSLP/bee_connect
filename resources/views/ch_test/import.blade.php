@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>ນຳເຂົ້າສິນຄ້າ</h3>
                </div>
            </div>
            <div class="clearfix"></div>

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

            <form method="POST" action="/importProductCh">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="x_panel">
                            <div>
                                <h2>ເລຶອກບ່ອນສົ່ງ</h2>
                            </div>
                            <div class="x-content">

                                <input type="hidden" name="receiver_branch_id" id="receiver_branch_id">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ແຂວງ</label>
                                            <select class="form-control form-control-sm" id="select_province" required>
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
                                            <select class="form-control form-control-sm" disabled id="select_district"
                                                required>
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
                                            <select class="form-control form-control-sm" disabled id="select_branch"
                                                required>
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
                                <div>
                                    <button type="button" onclick="handleSelectReceiveBranch()"
                                        class="btn btn-primary pull-right px-5">ຕົກລົງ</button>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row d-none" id="barcode_input_box">
                    <div class="col">
                        <div class="x_panel">
                            <div>
                                <h2>ສະແກນບາໂຄດ</h2>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ລະຫັດເຄື່ອງ</label>
                                            <div class="spinner-border d-none" id="loading" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <input class="form-control" id="product_id">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="row d-none" id="list_box">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <div class="x_panel">
                                    <div>
                                        <h2>ລາຍການ</h2>
                                    </div>
                                    <div class="x_content">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class=" text-primary">
                                                    <th>
                                                        ລະຫັດເຄື່ອງ
                                                    </th>
                                                    <th>
                                                        ນ້ຳໜັກ/ຂະໜາດ
                                                    </th>
                                                    <th>
                                                        ຫົວໜ່ວຍ
                                                    </th>
                                                </thead>
                                                <tbody id="product_item_table">

                                                </tbody>
                                            </table>
                                        </div>

                                        <hr>
                                        <hr>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ລາຄາຕົ້ນທຶນ (ກິໂລກຼາມ)</label>
                                                    <input class="form-control form-control-sm" name="base_price_kg">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ລາຄາ (ກິໂລກຼາມ)</label>
                                                    <input class="form-control form-control-sm" name="real_price_kg">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ນ້ຳໜັກລວມ (ກິໂລກຼາມ)</label>
                                                    <input class="form-control form-control-sm" name="weight_kg"
                                                        id="all_weight_kg" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ລາຄາຕົ້ນທຶນ (ແມັດກ້ອນ)</label>
                                                    <input class="form-control form-control-sm" name="base_price_m">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ລາຄາ (ແມັດກ້ອນ)</label>
                                                    <input class="form-control form-control-sm" name="real_price_m">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ຄ່າຂົນສົ່ງ</label>
                                                    <input class="form-control form-control-sm" name="fee">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ຄ່າເປົາ</label>
                                                    <input class="form-control form-control-sm" name="pack_price">
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary pull-right px-5">ບັນທຶກ</button>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </form>
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
                window.open(`importpdfCh/${product_id}`);
            }
        });

        var codes = [];
        var items = [];
        $('#product_id').keypress(function(event) {
            if (event.keyCode == 13) {
                let code = $('#product_id').val();
                if (code == '') {
                    alert("empty!!!");
                } else {
                    if (codes.includes(code)) {
                        $('#product_id').val('');
                        alert("ລະຫັດຊ້ຳ");
                    } else {
                        $("#product_id").prop('disabled', true);
                        $("#loading").removeClass('d-none');
                        checkItem(code)
                        $('#product_id').val('');
                    }
                }
            }
        });

        function checkItem(code) {
            let receive_branch = $("#select_branch").val();
            $.ajax({
                type: 'POST',
                url: '/checkImportProductCh',
                data: {
                    id: code,
                    receive_branch: receive_branch,
                    '_token': $('meta[name=csrf-token]').attr('content')
                },
                success: function(data) {
                    $("#product_id").prop('disabled', false);
                    $("#loading").addClass('d-none');
                    $("#product_id").focus();
                    if (!data.error) {
                        items.push({
                            code: code,
                            weight_type: 'kg',
                            weight: 0,
                        })
                        codes.push(code);
                        generateItem();
                    } else {
                        alert("ບໍ່ມີສິນຄ້ານີ້ ຫຼື ບໍ່ແມ່ນຂອງສາຂານີ້!!!");
                    }

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#product_id").prop('disabled', false);
                    $("#loading").addClass('d-none');
                    $("#product_id").focus();
                    alert("ບໍ່ມີສິນຄ້ານີ້!!!");
                }

            });
        }

        function deleteItem(id) {
            codes = codes.filter(code => code !== id);
            items = items.filter(item => item.code !== id);
            $('#product_item_table').html('');
            generateItem();

        }

        function generateItem() {
            var html_table = '';
            items.slice().reverse().forEach(item => {
                html_table +=
                    `<tr><td class="py-0"><div class="form-group"><input value='${item.code}' class="form-control form-control-sm" name="item_id[]" required></div></td><td class="py-0"><div class="form-group"><input type="number" value=${item.weight} step="0.001" class="form-control form-control-sm" name="weight[]" onchange=changeWeight(this.value,'${item.code}') required></div></td><td class="py-0"><div class="form-group"><select onchange=changeWeightType(this.value,'${item.code}') class="form-control form-control-sm" name="weight_type[]"required><option value="kg" ${item.weight_type !=='m'?'selected':''}>ກິໂລກຼາມ</option> <option value="m" ${item.weight_type ==='m'?'selected':''}>ແມັດກ້ອນ</option></select></div></td><td class="py-0"><div class="form-group"><a type="button" onclick=deleteItem("${item.code}")> <i class="material-icons">clear</i></a></div></td></tr>`
            })
            $('#product_item_table').html(html_table)
        }

        function changeWeight(weight, code) {
            old_item = items.filter(item => item.code === code);
            var o_index = items.findIndex(item => item.code === code);
            items = items.filter(item => item.code !== code);
            items.splice(o_index, 0, {
                code: code,
                weight: weight,
                weight_type: old_item[0].weight_type,
            });
        }

        function changeWeightType(weight_type, code) {
            old_item = items.filter(item => item.code === code);
            var o_index = items.findIndex(item => item.code === code);
            items = items.filter(item => item.code !== code);
            items.splice(o_index, 0, {
                code: code,
                weight: old_item[0].weight,
                weight_type: weight_type,
            });

            if (items.filter(filter => filter.weight_type === 'kg').length <= 0) {
                $("#all_weight_kg").attr("required", false);
            } else {
                $("#all_weight_kg").attr("required", true);
            }

        }

        function handleSelectReceiveBranch() {
            $("#receiver_branch_id").val($("#select_branch").val());
            $("#select_province").attr("disabled", true);
            $("#select_district").attr("disabled", true);
            $("#select_branch").attr("disabled", true);
            $("#barcode_input_box").removeClass("d-none");
            $("#list_box").removeClass("d-none");
            $("#product_id").focus();
        }

    </script>
@endsection
