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

            @if (Auth::user()->is_admin != 1)
                <div class="row">
                    <div class="col">
                        <div class="x_panel">
                            <div>
                                <h2>ຮັບສິນຄ້າ</h2>
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
            @endif

            <form method="POST" action="/importProductForUserCh">
                @csrf
                <div class="row">
                    <div class="col-md-12">
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
                                        </thead>
                                        <tbody id="product_item_table">

                                        </tbody>
                                    </table>
                                    <div>
                                        <button type="submit" class="btn btn-primary pull-right px-5">ບັນທຶກ</button>
                                        <div class="clearfix"></div>
                                    </div>
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

            $("#product_id").focus();

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
                $('#product_id').val('');
                if (code == '') {
                    alert("empty!!!");
                } else {
                    if (codes.includes(code)) {
                        alert("ລະຫັດຊ້ຳ");
                    } else {
                        $("#product_id").prop('disabled', true);
                        $("#loading").removeClass('d-none');
                        checkItem(code);

                        $('#product_id').val('');
                    }
                }
            }
        });

        function deleteItem(id) {
            codes = codes.filter(code => code !== id);
            items = items.filter(item => item.code !== id);
            console.log(items);

            $('#product_item_table').html('');
            generateItem();

        }

        function checkItem(code) {

            $.ajax({
                type: 'POST',
                url: '/getImportProductCh',
                data: {
                    id: code,
                    '_token': $('meta[name=csrf-token]').attr('content')
                },
                success: function(data) {
                    $("#product_id").prop('disabled', false);
                    $("#loading").addClass('d-none');
                    $("#product_id").focus();
                    if (!data.error && data.status == 'sending') {
                        codes.push(code);
                        items.push(data);
                        generateItem();
                    } else if (!data.error && data.status == 'success') {
                        alert("ສິນຄ້ານີ້ຂາຍອອກແລ້ວ!!!");
                    } else {
                        alert("ບໍ່ມີສິນຄ້ານີ້!!!");
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

        function generateItem() {
            var html_table = '';
            items.slice().reverse().forEach(item => {
                html_table +=
                    `<tr><td class="py-0"><div class="form-group"><input value='${item.code}' class="form-control form-control-sm" readonly><input type="hidden" value='${item.id}' name="item_id[]" ></div></td><td class="py-0"><div class="form-group"><a type="button" onclick=deleteItem("${item.code}")> <i class="material-icons">clear</i></a></div></td></tr>`
            })
            $('#product_item_table').html(html_table)
        }

    </script>
@endsection
