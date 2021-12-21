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

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title">ສະແກນບາໂຄດ</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="bmd-label-floating h6">ລະຫັດເຄື່ອງ</label>
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

            <br>

            <form method="POST" action="/importProduct">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title">ລາຍການ</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="font-weight-bold">
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

                                {{-- <div>
                                <button type="submit" class="btn btn-primary pull-right px-5">ບັນທຶກ</button>
                                <div class="clearfix"></div>
                            </div> --}}

                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="x_panel">
                            <div class="x_content">
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
                                            <input class="form-control form-control-sm" name="weight_kg" id="all_weight_kg"
                                                required>
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title">ຄ່າບໍລິການເພີ່ມເຕີມ</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ຊື່ບໍລິການ</label>
                                            <input class="form-control form-control-sm" id="service_charge_name">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ລາຄາ</label>
                                            <input class="form-control form-control-sm" id="service_charge_price">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-sm btn-info px-3"
                                            onclick="addServiceCharge()">ເພີ່ມ</button>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="font-weight-bold">
                                                <th>
                                                    ລະຫັດເຄື່ອງ
                                                </th>
                                                <th>
                                                    ນ້ຳໜັກ/ຂະໜາດ
                                                </th>
                                            </thead>
                                            <tbody id="service_item_table">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title">ເລຶອກບ່ອນສົ່ງ</h5>
                            </div>
                            <div class="card-body">
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
                                <div>
                                    <button type="submit" class="btn btn-info pull-right px-5">ບັນທຶກ</button>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </form>

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        var district_lists = <?php echo json_encode($districts); ?>;;
        var branch_lists = <?php echo json_encode($branchs); ?>;;
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
                        items.push({
                            code: code,
                            weight_type: 'kg',
                            weight: 0,
                        })
                        codes.push(code);
                        generateItem();
                        $('#product_id').val('');
                    }
                }
            }
        });

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

        var service_charge = []

        function addServiceCharge() {
            if ($("#service_charge_name").val() === "" || $("#service_charge_price").val() === "") {
                alert("ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບ")
            } else {

                service_charge.push({
                    id: service_charge.length + 1,
                    name: $("#service_charge_name").val(),
                    price: $("#service_charge_price").val()
                });
                generateServiceChargeItems();
                $("#service_charge_name").val("")
                $("#service_charge_price").val("")
            }
        }

        function generateServiceChargeItems() {
            let service_charge_html = service_charge.map(val =>
                `<tr><td class="py-0"><div class="form-group"><input value='${val.name}' class="form-control form-control-sm" name="service_item_name[]" required readonly></div></td><td class="py-0"><div class="form-group"><input type="number" value=${val.price} step="0.001" class="form-control form-control-sm" name="service_item_price[]" required readonly></div></td><td class="py-0"><div class="form-group"><a type="button" onclick=deleteServiceItem(${val.id})> <i class="material-icons">clear</i></a></div></td></tr>`
            );
            $("#service_item_table").html(service_charge_html)

        }

        function deleteServiceItem(id) {
            service_charge = service_charge.filter(val => val.id !== id);
            generateServiceChargeItems();
        }
    </script>
@endsection
