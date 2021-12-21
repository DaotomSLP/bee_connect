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
            <br>
            <form method="POST" action="/insertSaleImport">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title">ລາຍການ</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="font-weight-bold">
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
                                </div>
                                <hr>
                                <div class="row my-3">
                                    <div class="col-6">
                                        <label class="bmd-label-floating">ສ່ວນຫຼຸດ</label>
                                        <div class="form-group"><input type="number"
                                                class="form-control form-control-sm" name="discount" id="discount_input"
                                                onkeypress="disableSubmit()">
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-3">
                                    <div class="col">
                                        <p class="d-inline pr-3 h5">ລວມເປັນເງິນ : </p>
                                        <p class="d-inline h5" id="total">0 ກີບ</p>
                                    </div>
                                </div>

                                <div>
                                    <button type="button" onclick="calcurateTotal()"
                                        class="btn btn-info pull-left px-5 mr-3">ຄິດໄລ່ເງິນ</button>
                                </div>
                                <div>
                                    <button type="submit" id="submitBtn" disabled class="btn btn-info px-5">ບັນທຶກ</button>
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
        $(document).ready(function() {

            $("#product_id").focus();

            var product_id =
                "<?php echo session()->get('id') ? session()->get('id') : 'no_id'; ?>";

            if (product_id != 'no_id') {
                window.open(`salepdf/${product_id}`);
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
                        alert("ລະຫັດຊ້ຳ");
                        $('#product_id').val('');
                    } else {
                        $("#product_id").prop('disabled', true);
                        $("#loading").removeClass('d-none');
                        checkItem(code);
                        $('#product_id').val('');
                    }
                }
            }
        });

        var total = 0;

        var gram_price = <?php echo isset($sale_price_gram) ? $sale_price_gram['price'] : 0; ?>;;
        var m_price = <?php echo isset($sale_price_m) ? $sale_price_m['price'] : 0; ?>;

        function checkItem(code) {

            $.ajax({
                type: 'POST',
                url: '/getImportProduct',
                data: {
                    id: code,
                    '_token': $('meta[name=csrf-token]').attr('content')
                },
                success: function(data) {
                    $("#product_id").prop('disabled', false);
                    $("#loading").addClass('d-none');
                    $("#product_id").focus();
                    if (!data.error && data.status == 'received') {
                        codes.push(code);
                        items.push({
                            id: data.id,
                            code: code,
                            weight_type: data.weight_type,
                            weight: data.weight,
                            price: data.weight_type === 'm' ? m_price : gram_price,
                            amount: data.weight * (data.weight_type === 'm' ? m_price : gram_price)
                        })
                        generateItem();
                    } else if (!data.error && data.status == 'sending') {
                        alert("ສິນຄ້ານີ້ຍັງບໍ່ທັນໄດ້ຮັບ!!!");
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
                    `<tr><td class="py-0"><div class="form-group"><input value='${item.code}' class="form-control form-control-sm" readonly><input type="hidden" value='${item.id}' name="item_id[]"></div></td> <td class="py-0"><div class="form-group"><input type="number" step="0.001" class="form-control form-control-sm weight" name="weight[]" value='${item.weight}' ${item.weight_type === "m" ?'':''} onchange=changeWeight(this.value,'${item.code}','${item.weight_type}','${item.id}') onkeypress="disableSubmit()" required></div></td> <td class="py-0"><div class="form-group"><input class="form-control form-control-sm"  value='${item.price}' name="sale_price[]" onchange=changePrice(this.value,'${item.code}','${item.weight_type}','${item.id}') onkeypress="disableSubmit()" required></div></td><td class="py-0"><div class="form-group"><a type="button" onclick=deleteItem("${item.code}")> <i class="material-icons">clear</i></a></div></td></tr>`;

            })
            $('#product_item_table').html(html_table)
        }

        function changeWeight(weight, code, weight_type, id) {
            old_item = items.filter(item => item.code === code);
            var o_index = items.findIndex(item => item.code === code);
            items = items.filter(item => item.code !== code);
            items.splice(o_index, 0, {
                id: id,
                code: code,
                weight: weight,
                weight_type: weight_type,
                price: old_item[0].price,
                amount: weight * old_item[0].price
            });
        }

        function changePrice(price, code, weight_type, id) {
            old_item = items.filter(item => item.code === code);
            var o_index = items.findIndex(item => item.code === code);
            items = items.filter(item => item.code != code);
            items.splice(o_index, 0, {
                id: id,
                code: code,
                weight: old_item[0].weight,
                weight_type: weight_type,
                price: price,
                amount: old_item[0].weight * price
            });

        }

        function deleteItem(id) {
            codes = codes.filter(code => code !== id);
            items = items.filter(item => item.code !== id);

            $('#product_item_table').html('');
            generateItem();
            disableSubmit()
        }

        function calcurateTotal() {
            total = items.reduce((total, amount) => (typeof total === 'object' ? total.amount : total) + amount.amount);
            var grandTotal = (typeof total === 'object' ? total.amount : total) - $("#discount_input").val();
            $("#total").html(`${grandTotal} ກີບ`)
            $("#submitBtn").prop('disabled', false);
        }

        function disableSubmit() {
            $("#submitBtn").prop('disabled', true);
        }
    </script>
@endsection
