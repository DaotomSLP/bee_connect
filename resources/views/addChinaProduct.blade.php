@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>ສັ່ງນຳເຂົ້າສິນຄ້າ</h3>
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
            <div class="row">
                <div class="col">
                    <form method="POST" action="/insertChinaProduct">
                        @csrf
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
                                                        ຮູບແບບການສົ່ງ
                                                    </th>
                                                    <th>
                                                        ລາຍລະອຽດບ່ອນສົ່ງ
                                                    </th>
                                                </thead>
                                                <tbody id="product_item_table">

                                                </tbody>
                                            </table>
                                        </div>

                                        <hr>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary pull-right px-5">ບັນທຶກ</button>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
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
                            delivery_type: 'normal',
                            addr_detail: '',
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
                    `<tr><td class="py-0"><div class="form-group"><input value='${item.code}' class="form-control form-control-sm" name="item_id[]" required></div></td><td class="py-0"><div class="form-group"><select onchange=changeDeliveryType(this.value,'${item.code}') class="form-control form-control-sm" name="delivery_type[]"required><option value="normal" ${item.delivery_type !=='normal'?'selected':''}>ສົ່ງທົ່ວໄປ</option> <option value="tohouse" ${item.delivery_type ==='tohouse'?'selected':''}>ສົ່ງຮອດບ້ານ</option></select></div></td><td class="py-0"><div class="form-group"> <textarea class="form-control" onchange=changeAddrDetail(this.value,'${item.code}') name="addr_detail[]">${item.addr_detail}</textarea></div></td><td class="py-0"><div class="form-group"><a type="button" onclick=deleteItem("${item.code}")> <i class="material-icons">clear</i></a></div></td></tr>`
            })
            $('#product_item_table').html(html_table)
        }

        function changeDeliveryType(delivery_type, code) {
            old_item = items.filter(item => item.code === code);
            var o_index = items.findIndex(item => item.code === code);
            items = items.filter(item => item.code !== code);
            items.splice(o_index, 0, {
                code: code,
                addr_detail: old_item[0].addr_detail,
                delivery_type: delivery_type,
            });
        }

        function changeAddrDetail(addr_detail, code) {
            old_item = items.filter(item => item.code === code);
            var o_index = items.findIndex(item => item.code === code);
            items = items.filter(item => item.code !== code);
            items.splice(o_index, 0, {
                code: code,
                addr_detail: addr_detail,
                delivery_type: old_item[0].delivery_type,
            });
        }

    </script>
@endsection
