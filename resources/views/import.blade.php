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
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h5 class="card-title">ສະແກນບາໂຄດ</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ລະຫັດເຄື່ອງ</label>
                                        <input class="form-control" id="product_id">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form method="POST" action="/importProduct">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h5 class="card-title ">ລາຍການ</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
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
                                            <input class="form-control form-control-sm" name="weight_kg" required>
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
                            </div>

                        </div>
                    </div>
                </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header card-header-primary">
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
                                    <select class="form-control form-control-sm" disabled id="select_district" required>
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
                            <button type="submit" class="btn btn-primary pull-right px-5">ບັນທຶກ</button>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
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
            $('#product_item_table').after(
                `<tr><td class="py-0"><div class="form-group"><input value='${code}' class="form-control form-control-sm" name="item_id[]" required></div></td><td class="py-0"><div class="form-group"><input type="number" value=0 step="0.001" class="form-control form-control-sm" name="weight[]" required></div></td><td class="py-0"><div class="form-group"><select class="form-control form-control-sm" name="weight_type[]"required><option value="gram">ກິໂລກຼາມ</option> <option value="m">ແມັດກ້ອນ</option></select></div></td></tr>`
            )
        }

    </script>
@endsection
