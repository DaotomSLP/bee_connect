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

        <div class="d-none" id="success_alert">
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div>
                        <h2>ທົ່ວໄປ</h2>
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
                                <div class="form-group"><input type="number" class="form-control form-control-sm" name="discount" id="discount_input" onkeypress="disableSubmit()">
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col">
                                <p class="d-inline pr-3 h5">ລວມເປັນເງິນ : </p>
                                <p class="d-inline h5" id="total">0 ບາດ</p>
                            </div>
                        </div>

                        <div>
                            <button type="button" onclick="calcurateTotal()" class="btn btn-primary pull-left px-5 mr-3">ຄິດໄລ່ເງິນ</button>
                        </div>
                        <div>
                            <button type="button" onclick="handleSave()" id="submitBtn" disabled class="btn btn-primary px-5">ບັນທຶກ</button>
                            <div class="clearfix"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- --}}

        <!-- <div class="d-none" id="success_alert_for_rider">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div>
                            <h2>ສົ່ງເຖິງບ້ານ</h2>
                        </div>
                        <div class="x_content">
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
                                        <th>
                                            ຄ່າຂົນສົ່ງ
                                        </th>
                                    </thead>
                                    <tbody id="product_item_table_for_rider">

                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="row my-3">
                                <div class="col-6">
                                    <label class="bmd-label-floating">ສ່ວນຫຼຸດ</label>
                                    <div class="form-group"><input type="number" class="form-control form-control-sm"
                                            name="discount_for_rider" id="discount_input_for_rider"
                                            onkeypress="disableSubmitForRider()">
                                    </div>
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col">
                                    <p class="d-inline pr-3 h5">ລວມເປັນເງິນ : </p>
                                    <p class="d-inline h5" id="total_for_rider">0 ບາດ</p>
                                </div>
                            </div>

                            <div>
                                <button type="button" onclick="calcurateTotalForRider()"
                                    class="btn btn-primary pull-left px-5 mr-3">ຄິດໄລ່ເງິນ</button>
                            </div>
                            <div>
                                <button type="button" onclick="handleSaveForRider()" id="submitBtn_for_rider" disabled
                                    class="btn btn-primary px-5">ບັນທຶກ</button>
                                <div class="clearfix"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div> -->
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {

        $("#product_id").focus();

        var product_id =
            "<?php echo session()->get('id') ? session()->get('id') : 'no_id'; ?>";

        if (product_id != 'no_id') {
            window.open(`salepdfTh/${product_id}`);
        }

    });

    var codes = [];
    var codes_for_rider = [];
    var items = [];
    var items_for_rider = [];

    $('#product_id').keypress(function(event) {
        if (event.keyCode == 13) {
            let code = $('#product_id').val();
            if (code == '') {
                alert("empty!!!");
            } else {
                if (codes.includes(code) || codes_for_rider.includes(code)) {
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

    var gram_price = <?php echo isset($sale_price_gram) ? $sale_price_gram['price'] :
                            0; ?>;;
    var m_price = <?php echo isset($sale_price_m) ? $sale_price_m['price'] : 0; ?>;

    function checkItem(code) {

        $.ajax({
            type: 'POST',
            url: '/getImportProductTh',
            data: {
                id: code,
                '_token': $('meta[name=csrf-token]').attr('content')
            },
            success: function(data) {
                $("#product_id").prop('disabled', false);
                $("#loading").addClass('d-none');
                $("#product_id").focus();
                if (!data.error && data.status == 'received') {
                    // if (data.delivery_type === "tohouse") {
                    //     codes_for_rider.push(code);
                    //     items_for_rider.push({
                    //         id: data.id,
                    //         code: code,
                    //         weight_type: data.weight_type,
                    //         weight: data.weight,
                    //         price: data.weight_type === 'm' ? m_price : gram_price,
                    //         amount: data.weight * (data.weight_type === 'm' ? m_price : gram_price),
                    //         shipping_fee: data.shipping_fee === null ? 0 : data.shipping_fee,
                    //         amount_shipping_fee: (data.shipping_fee === null ? 0 : data
                    //             .shipping_fee) * data.weight
                    //     })
                    //     generateItemForRider();
                    // } else {
                    codes.push(code);
                    items.push({
                        id: data.id,
                        code: code,
                        price: 0,
                        weight: data.weight
                    })
                    generateItem();
                    // }
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
                `<tr><td class="py-0"><div class="form-group"><input value='${item.code}' class="form-control form-control-sm" readonly><input type="hidden" value='${item.id}' name="item_id[]"></div></td> <td class="py-0"><div class="form-group"><input class="form-control form-control-sm"  value='${item.weight}' readonly></div></td><td class="py-0"><div class="form-group"><input class="form-control form-control-sm"  value='${item.price}' name="sale_price[]" onchange=changePrice(this.value,'${item.code}','${item.id}') onkeypress="disableSubmit()" required></div></td><td class="py-0"><div class="form-group"><a type="button" onclick=deleteItem("${item.code}")> <i class="material-icons">clear</i></a></div></td></tr>`;

        })
        $('#product_item_table').html(html_table)
    }

    function changePrice(price, code, id) {
        old_item = items.filter(item => item.code === code);
        var o_index = items.findIndex(item => item.code === code);
        items = items.filter(item => item.code != code);
        items.splice(o_index, 0, {
            id: id,
            code: code,
            price: price,
            weight: old_item[0].weight
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
        total = items.reduce((total, price) => (typeof total === 'object' ? parseInt(total.price) : parseInt(total)) + parseInt(price.price));
        var grandTotal = (typeof total === 'object' ? total.price : total) - $("#discount_input").val();
        $("#total").html(`${grandTotal} ບາດ`)
        $("#submitBtn").prop('disabled', false);
    }

    function disableSubmit() {
        $("#submitBtn").prop('disabled', true);
    }

    var total_for_rider = 0;

    function generateItemForRider() {
        var html_table = '';
        items_for_rider.slice().reverse().forEach(item => {

            html_table +=
                `<tr><td class="py-0"><div class="form-group"><input value='${item.code}' class="form-control form-control-sm" readonly><input type="hidden" value='${item.id}' name="item_id[]"></div></td> <td class="py-0"><div class="form-group"><input type="number" step="0.001" class="form-control form-control-sm weight" name="weight_for_rider" value='${item.weight}' ${item.weight_type === "m" ?'readonly':''} onchange=changeWeightForRider(this.value,'${item.code}','${item.weight_type}','${item.id}','}') onkeypress="disableSubmitForRider()" required></div></td> <td class="py-0"><div class="form-group"><input class="form-control form-control-sm"  value='${item.price}' name="sale_price[]" onchange=changePriceForRider(this.value,'${item.code}','${item.weight_type}','${item.id}') onkeypress="disableSubmitForRider()" required></div></td><td class="py-0"><div class="form-group"><input name="shipping_fee[]" value="${item.shipping_fee}" class="form-control form-control-sm" type="number" onchange=changeShippingFeeForRider('${item.code}','${item.weight_type}','${item.id}',this.value) onkeypress="disableSubmitForRider()"></div></td><td class="py-0"><div class="form-group"><a type="button" onclick=deleteItemForRider("${item.code}")> <i class="material-icons">clear</i></a></div></td></tr>`;

        })
        $('#product_item_table_for_rider').html(html_table)
    }

    function changeWeightForRider(weight, code, weight_type, id) {
        old_item = items_for_rider.filter(item => item.code === code);
        var o_index = items_for_rider.findIndex(item => item.code === code);
        items_for_rider = items_for_rider.filter(item => item.code !== code);
        items_for_rider.splice(o_index, 0, {
            id: id,
            code: code,
            weight: weight,
            weight_type: weight_type,
            price: old_item[0].price,
            amount: weight * old_item[0].price,
            shipping_fee: old_item[0].shipping_fee,
            amount_shipping_fee: weight * old_item[0].shipping_fee,
        });
    }

    function changePriceForRider(price, code, weight_type, id) {
        old_item = items_for_rider.filter(item => item.code === code);
        var o_index = items_for_rider.findIndex(item => item.code === code);
        items_for_rider = items_for_rider.filter(item => item.code != code);
        items_for_rider.splice(o_index, 0, {
            id: id,
            code: code,
            weight: old_item[0].weight,
            weight_type: weight_type,
            price: price,
            amount: old_item[0].weight * price,
            shipping_fee: old_item[0].shipping_fee,
            amount_shipping_fee: old_item[0].weight * old_item[0].shipping_fee,
        });
    }

    function changeShippingFeeForRider(code, weight_type, id, shipping_fee) {
        old_item = items_for_rider.filter(item => item.code === code);
        var o_index = items_for_rider.findIndex(item => item.code === code);
        items_for_rider = items_for_rider.filter(item => item.code != code);
        items_for_rider.splice(o_index, 0, {
            id: id,
            code: code,
            weight: old_item[0].weight,
            weight_type: weight_type,
            price: old_item[0].price,
            amount: old_item[0].weight * old_item[0].price,
            shipping_fee: shipping_fee,
            amount_shipping_fee: old_item[0].weight * shipping_fee
        });
    }

    function deleteItemForRider(id) {
        codes_for_rider = codes_for_rider.filter(code => code !== id);
        items_for_rider = items_for_rider.filter(item => item.code !== id);

        $('#product_item_table_for_rider').html('');
        generateItem();
        disableSubmit()
    }

    function calcurateTotalForRider() {
        total_for_rider = items_for_rider.reduce((total, amount) => (typeof total === 'object' ? (total.amount + total
                .amount_shipping_fee) : total) +
            (amount.amount + amount
                .amount_shipping_fee));
        var grandTotal = (typeof total_for_rider === 'object' ? total_for_rider.amount + parseInt(total_for_rider
                .amount_shipping_fee) :
            total_for_rider) - $("#discount_input_for_rider").val();
        $("#total_for_rider").html(`${grandTotal} ບາດ`)
        $("#submitBtn_for_rider").prop('disabled', false);
    }

    function disableSubmitForRider() {
        $("#submitBtn_for_rider").prop('disabled', true);
    }

    function handleSave() {
        $("#submitBtn").prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: '/insertSaleImportTh',
            data: {
                items: items,
                discount: $("#discount_input").val(),
                '_token': $('meta[name=csrf-token]').attr('content')
            },
            success: function(res) {
                console.log(res.id);
                if (res.id !== 0) {
                    window.open(`salepdfTh/${res.id}`);
                    items = [];
                    total = 0;
                    codes = [];
                    generateItem();
                    $("#total").html(`0 ບາດ`)
                    $("#discount_input").val(0)
                    $("#success_alert").removeClass("d-none")
                    $("#success_alert").html(renderSuccessAlert())
                } else {
                    alert('ເກີດຂໍ້ພິດພາດກະລຸນາລອງໃໝ່')
                    $("#submitBtn").prop('disabled', false);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert('ເກີດຂໍ້ພິດພາດກະລຸນາລອງໃໝ່')
                $("#submitBtn").prop('disabled', false);
            }

        });
    }

    function handleSaveForRider() {
        $("#submitBtn_for_rider").prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: '/insertSaleImportForRiderTh',
            data: {
                items: items_for_rider,
                discount: $("#discount_input_for_rider").val(),
                '_token': $('meta[name=csrf-token]').attr('content')
            },
            success: function(res) {
                console.log(res.id);
                if (res.id !== 0) {
                    window.open(`salepdfTh/${res.id}`);
                    items_for_rider = [];
                    total_for_rider = 0;
                    codes_for_rider = [];
                    generateItemForRider();
                    $("#total_for_rider").html(`0 ບາດ`)
                    $("#discount_input_for_rider").val(0)
                    $("#success_alert_for_rider").removeClass("d-none")
                    $("#success_alert_for_rider").html(renderSuccessAlert())
                } else {
                    alert('ເກີດຂໍ້ພິດພາດກະລຸນາລອງໃໝ່')
                    $("#submitBtn_for_rider").prop('disabled', false);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert('ເກີດຂໍ້ພິດພາດກະລຸນາລອງໃໝ່')
                $("#submitBtn_for_rider").prop('disabled', false);
            }

        });
    }

    function renderSuccessAlert() {
        return `<div class="alert alert-success">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <i class="material-icons">close</i>
                                                        </button>
                                                        <span>
                                                            <b> Success - </b>ບັນທຶກຂໍ້ມູນສຳເລັດ</span>
                                                    </div>`
    }
</script>
@endsection