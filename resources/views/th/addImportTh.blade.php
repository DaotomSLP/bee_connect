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


            <div class="clearfix"></div>
            <div class="row">
                <div class="col">
                    <form method="POST" action="/addImportProductTh">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="x_panel">
                                    <div class="x_content">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ລະຫັດສິນຄ້າ</label>
                                                    <input class="form-control form-control-sm" name="code">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ຊື່ສິນຄ້າ</label>
                                                    <input class="form-control form-control-sm" name="name">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ລາຍລະອຽດບ່ອນສົ່ງ</label>
                                                    <textarea class="form-control" name="detail"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="row">
                            <div class="col">
                                <div class="x_panel">
                                    <div>
                                        <h2>ເລຶອກບ່ອນສົ່ງ</h2>
                                    </div>
                                    <div class="x-content">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ແຂວງ</label>
                                                    <select class="form-control form-control-sm" id="select_province"
                                                        required>
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
                                                    <select class="form-control form-control-sm" disabled
                                                        id="select_district" required>
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
                window.open(`addImportThPdf/${product_id}`);
            }
        });
    </script>
@endsection
