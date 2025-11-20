@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>ລາຍຈ່າຍ</h3>
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
                                <h2 class="card-title">ແກ້ໄຂລາຍຈ່າຍ</h2>
                            </div>
                            <div class="x_content">
                                <form method="POST" action="/updateExpenditure" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $expenditure->id }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ວັນທີ</label>
                                                <input class="form-control" type="date"
                                                    value="{{ date('Y-m-d', strtotime($expenditure->created_at)) }}"
                                                    name="date">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ຈຳນວນເງິນ</label>
                                                <input class="form-control" name="price"
                                                    value="{{ $expenditure->price }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ລາຍລະອຽດ</label>
                                                <textarea class="form-control" name="detail">{{ $expenditure->detail }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ເລືອກຖ້ຽວລົດ</label>
                                                <select class="form-control form-control-sm" id="select_delivery_round"
                                                    name="delivery_round_id" required>
                                                    <option value="">
                                                        ເລືອກ
                                                    </option>
                                                    @foreach ($delivery_rounds as $key => $delivery_round)
                                                        <option value="{{ $delivery_round->id }}"
                                                            {{ $expenditure->delivery_round_id == $delivery_round->id ? 'selected' : '' }}>
                                                            ຖ້ຽວທີ່ {{ $delivery_round->round }} ເດືອນ
                                                            {{ $delivery_round->month }} ລົດວັນທີ່
                                                            {{ $delivery_round->departure_time }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- อัปโหลดใบเสร็จ -->
                                            <div class="custom-file mt-3 mb-3">
                                                <input type="file" class="custom-file-input" name="receipt"
                                                    id="thumbnailInput" accept="image/*" onchange="previewReceipt(event)">
                                                <label class="custom-file-label" for="thumbnailInput" id="fileLabel">Choose
                                                    file</label>
                                            </div>

                                            <!-- แสดงรูป preview -->
                                            <div class="text-center">
                                                <img id="receiptPreview" src="{{ '/img/receipts/' . $expenditure->receipt_image }}" alt=""
                                                    style="max-width:50%; border-radius:10px; margin-top:10px;">
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

        <!-- ✅ Script แสดงชื่อไฟล์ + preview รูป -->
        <script>
            function previewReceipt(event) {
                const input = event.target;
                const file = input.files[0];
                const label = document.getElementById('fileLabel');
                const preview = document.getElementById('receiptPreview');

                if (file) {
                    // แสดงชื่อไฟล์
                    label.textContent = file.name;

                    // แสดง preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    label.textContent = 'Choose file';
                    preview.src = '';
                }
            }
        </script>

    </div>
@endsection
