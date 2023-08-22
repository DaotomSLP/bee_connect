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
            @elseif(session()->get('error') == 'insert_success')
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

            <form method="POST" action="/lostProduct">
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
                                        </thead>
                                        <tbody id="product_item_table">

                                        </tbody>
                                    </table>
                                </div>

                                <hr>

                            </div>
                            <div class="card-footer">
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
                    `<tr><td class="py-0"><div class="form-group"><input value='${item.code}' class="form-control form-control-sm" name="item_id[]" required></div></td><td class="py-0"><div class="form-group"><input type="number" value=${item.weight} step="0.001" class="form-control form-control-sm" name="weight[]" onchange=changeWeight(this.value,'${item.code}') required></div></td><td class="py-0"><div class="form-group"><a type="button" onclick=deleteItem("${item.code}")> <i class="material-icons">clear</i></a></div></td></tr>`
            })
            $('#product_item_table').html(html_table)
        }

        function changeWeight(weight, code) {
            old_item = items.filter(item => item.code === code);
            var o_index = items.findIndex(item => item.code === code);
            items = items.filter(item => item.code !== code);
            items.splice(o_index, 0, {
                code: code,
                weight: weight
            });
        }
    </script>
@endsection
