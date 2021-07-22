@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h5 class="card-title ">ລາຍການບໍລິການເພີ່ມເຕີມ</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="/editServiceCharge">
                            @if (isset($service_charges) && sizeof($service_charges) > 0)

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class=" text-primary">
                                            <th>
                                                ລ/ດ
                                            </th>
                                            <th>
                                                ຊື່ບໍລິການ
                                            </th>
                                            <th>
                                                ຈຳນວນເງິນ
                                            </th>
                                            <th>

                                            </th>
                                        </thead>
                                        <tbody>

                                                @csrf
                                                @foreach ($service_charges as $key => $service_charge)
                                                    <tr>
                                                        <td>
                                                            {{ $key + 1 }}
                                                        </td>
                                                        <td>
                                                            <input type="hidden" value="{{ $service_charge->id }}" name="service_item_id[]">
                                                            <input class="form-control form-control-sm" value="{{ $service_charge->name }}" name="service_item_name[]">
                                                        </td>
                                                        <td>
                                                            <input class="form-control form-control-sm" value="{{ $service_charge->price }}" name="service_item_price[]">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" value="{{ $service_charges[0]->lot_id }}" name="lot_id">
                            <button type="submit" class="btn btn-primary pull-right px-5">ບັນທຶກການແກ້ໄຂ</button>
                            <div class="clearfix"></div>
                            @endif
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        function change_price(id, lot_id, base_price, real_price, weight, weight_type) {
            $("#lot_item_id").val(id);
            $("#lot_id").val(lot_id);
            $("#base_price").val(base_price);
            $("#real_price").val(real_price);
            $("#weight").val(weight);
            $("#weight_type").val(weight_type);
        }

        function change_weight(id, lot_id, base_price, real_price, old_weight) {
            $("#lot_item_id_in_weight").val(id);
            $("#lot_id_in_weight").val(lot_id);
            $("#base_price_in_weight").val(base_price);
            $("#real_price_in_weight").val(real_price);
            $("#old_weight_in_weight").val(old_weight);
            $("#weight_in_weight").val(old_weight);
        }

    </script>
@endsection
