
@extends('layout')

@section('body')
<!-- End Navbar -->
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>ແກ້ໄຂຜູ້ໃຊ້</h3>
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
            <div class="col-md-12">
                <div class="x_panel">
                    <div>
                        <h2 class="card-title">ແກ້ໄຂບັນຊີຜູ້ດູແລລະບົບ</h2>
                    </div>
                    <div class="x_content">
                        <form method="POST" action="/updateAdmin">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ຊື່</label>
                                        <input type="text" value="{{ $user['name'] }}" name="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ນາມສະກຸນ</label>
                                        <input type="text" value="{{ $user->last_name }}" name="last_name"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ອີເມວ</label>
                                        <input type="text" value="{{ $user->email }}" name="email" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ລະຫັດຜ່ານ</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ເບີໂທ</label>
                                        <input type="text" value="{{ $user->phone_no }}" name="phone_no"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary px-5">ບັນທຶກ</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection