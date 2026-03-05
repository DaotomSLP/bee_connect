@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>ຂໍ້ມູນເຄື່ອງເສຍ</h3>
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


            @if (Auth::user()->is_admin == 1)
                <div class="row">
                    <div class="col">
                        <div class="x_panel">
                            <div>
                                <h2 class="card-title">ແກ້ໄຂຂໍ້ມູນເຄື່ອງເສຍ</h2>
                            </div>
                            <div class="x_content">
                                <form method="POST" action="/update-parcel-issue">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $parcel_issue->id }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ເຄື່ອງວັນທີ</label>
                                                <input class="form-control" type="date"
                                                    value="{{ date('Y-m-d', strtotime($parcel_issue->received_at)) }}"
                                                    name="received_at">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ລະຫັດເຄື່ອງ</label>
                                                <input class="form-control" name="parcel_code"
                                                    value="{{ $parcel_issue->parcel_code }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ຈຳນວນເງິນ</label>
                                                <input class="form-control" name="parcel_price"
                                                    value="{{ $parcel_issue->parcel_price }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ເລືອກສາຂາ</label>
                                                <select class="form-control" id="select_branch" name="receiver_branch_id">
                                                    <option value="">
                                                        ເລືອກ
                                                    </option>
                                                    @foreach ($branchs as $key => $branch)
                                                        <option
                                                            {{ $parcel_issue->receiver_branch_id == $branch->id ? 'selected' : '' }}
                                                            value="{{ $branch->id }}">
                                                            {{ $branch->branch_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ລາຍລະອຽດ</label>
                                                <textarea class="form-control" name="detail">{{ $parcel_issue->detail }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary pull-right px-5">ບັນທຶກ</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            @endif
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <!-- ✅ Script แสดงชื่อไฟล์ + preview รูป -->
        <script>
            // 🧮 ฟังก์ชันฟอร์แมตเลข (ไม่มีทศนิยม)
            function formatIntegerInput(input) {
                // เก็บตำแหน่ง cursor ก่อนฟอร์แมต
                const start = input.selectionStart;
                const end = input.selectionEnd;

                // ลบ comma เดิมออก
                let value = input.value.replace(/,/g, '');
                // เอาเฉพาะตัวเลข
                value = value.replace(/\D/g, '');

                if (value === '') {
                    input.value = '';
                    return;
                }

                // ฟอร์แมต comma
                const formatted = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                input.value = formatted;

                // พยายามคืนตำแหน่ง cursor ให้ใกล้เคียงเดิม
                const diff = formatted.length - value.length;
                input.setSelectionRange(start + diff, end + diff);
            }

            // 🎯 ฟอร์แมตทุกครั้งที่พิมพ์
            $(document).on('input',
                'input[name="parcel_price"]',
                function() {
                    formatIntegerInput(this);
                }
            );

            $(document).ready(function() {
                formatIntegerInput($('input[name="parcel_price"]')[0]);
            });

            // 🚀 ลบ comma ก่อนส่งฟอร์ม (กัน backend อ่านค่าผิด)
            $('form').on('submit', function() {
                $('input[name="parcel_price"]').val($('input[name="parcel_price"]').val().replace(/,/g, ''));
            });
        </script>

    </div>
@endsection
