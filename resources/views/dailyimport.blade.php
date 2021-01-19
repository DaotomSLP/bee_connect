@extends('layout')

@section('body')
<!-- End Navbar -->
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>ລາຍງານປະຈຳວັນ</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">
            <div class="col">
                <h5>ລາຍງານປະຈຳວັນ :</h5>
                <form method="GET" action="home">
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <input class="form-control" type="date" value="{{ $date_now }}" name="date">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary pull-right px-5">ຄົ້ນຫາ</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-6">
                <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">attach_money</i>
                        </div>
                        <p class="card-category">ເງິນທີ່ໄດ້ຮັບທັງໝົດ</p>
                        <h5 class="card-title">{{ $sum_price }} &#8365;
                        </h5>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>

            @if (Auth::user()->is_admin == 1)
            <div class="col-6">
                <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">near_me</i>
                        </div>
                        <p class="card-category">
                            ຈຳນວນເຄື່ອງທີ່ສົ່ງຮອດແລ້ວທັງໝົດ
                        </p>
                        <h3 class="card-title">{{ $sum_delivery_received }}</h3>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-stats">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">near_me</i>
                        </div>
                        <p class="card-category">
                            ຈຳນວນເຄື່ອງທີ່ກຳລັງສົ່ງທັງໝົດ
                        </p>
                        <h3 class="card-title">{{ $sum_delivery_sending }}</h3>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
            @endif

            @if (Auth::user()->is_admin != 1)
            <div class="col-6">
                <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">transit_enterexit</i>
                        </div>
                        <p class="card-category">ຈຳນວນເຄື່ອງທີ່ຮັບເຂົ້າແລ້ວ</p>
                        <h3 class="card-title">{{ $sum_received }}</h3>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-stats">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">transit_enterexit</i>
                        </div>
                        <p class="card-category">ຈຳນວນເຄື່ອງທີ່ກຳລັງສົ່ງມາ</p>
                        <h3 class="card-title">{{ $sum_receive_sending }}</h3>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection