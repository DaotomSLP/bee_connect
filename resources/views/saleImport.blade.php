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
            <form method="POST" action="/insertSaleImport">
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
                                                ນ້ຳໜັກ
                                            </th>
                                            <th>
                                                ລາຄາຂາຍ
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
                        $('#product_id').val('');
                    } else {
                        generateItem(code);
                        codes.push(code);
                        $('#product_id').val('');
                    }
                }
            }
        });

        var gram_price = <?php echo isset($sale_price_gram) ? $sale_price_gram['price'] :
        0; ?> ;;
        var m_price = <?php echo isset($sale_price_m) ? $sale_price_m['price'] : 0; ?>;
        function generateItem(code) {

            $.ajax({
                type: 'POST',
                url: '/getImportProduct',
                data: {
                    id: code,
                    '_token': $('meta[name=csrf-token]').attr('content')
                },
                success: function(data) {

                    if (!data.error && data.status == 'received') {
                        $('#product_item_table').append(
                            `<tr><td class="py-0"><div class="form-group"><input value='${code}' class="form-control form-control-sm" name="item_id[]" required></div></td> <td class="py-0"><div class="form-group"><input type="number" step="0.001" class="form-control form-control-sm" name="weight[]" value='${data.weight_type === "m" ? data.weight : ''}' ${data.weight_type === "m" ?'readonly':''} required></div></td> <td class="py-0"><div class="form-group"><input class="form-control form-control-sm"  value='${data.weight_type === "m" ? m_price : gram_price}' name="sale_price[]" required></div></td></tr>`
                        )
                    } else if (!data.error && data.status == 'sending') {
                        alert("ສິນຄ້ານີ້ຍັງບໍ່ທັນໄດ້ຮັບ!!!");
                    } else if (!data.error && data.status == 'success') {
                        alert("ສິນຄ້ານີ້ຂາຍອອກແລ້ວ!!!");
                    } else {
                        alert("ບໍ່ມີສິນຄ້ານີ້!!!");
                    }

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("ບໍ່ມີສິນຄ້ານີ້!!!");
                }

            });
        }

    </script>
@endsection
