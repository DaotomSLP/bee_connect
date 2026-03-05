@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>ຂໍ້ມູນການຈ່າຍເງິນ</h3>
                </div>
            </div>
            <div class="clearfix"></div>

            @if (session()->get('error') == 'not_refund')
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                    <span>
                        <b> Danger - </b>ເກີດຂໍ້ຜິດພາດ ກະລຸນາລອງໃໝ່</span>
                </div>
            @elseif(session()->get('error') == 'refund_success')
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                    <span>
                        <b> Success - </b>ບັນທຶກຂໍ້ມູນສຳເລັດ</span>
                </div>
            @endif


            @if (Auth::user()->is_admin == 1)
                @if ($total_refund < $parcel_issue->parcel_price)
                    <div class="row">
                        <div class="col">
                            <div class="x_panel">
                                <div>
                                    <h2 class="card-title">ເພີ່ມຂໍ້ມູນການຈ່າຍເງິນ</h2>
                                </div>
                                <div class="x_content">
                                    <form method="POST" action="/insert-refund" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="parcel_issue_id" value="{{ $parcel_issue->id }}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ຈຳນວນເງິນ</label>
                                                    <input class="form-control" name="amount"
                                                        value="{{ $parcel_issue->amount }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <!-- อัปโหลดใบเสร็จ -->
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ບິນຈ່າຍເງິນ</label>
                                                    <div class="custom-file mb-3">
                                                        <input type="file" class="custom-file-input" name="receipt"
                                                            id="thumbnailInput" accept="image/*"
                                                            onchange="previewReceipt(event)">
                                                        <label class="custom-file-label" for="thumbnailInput"
                                                            id="fileLabel">Choose
                                                            file</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">ໝາຍເຫດ</label>
                                                    <textarea class="form-control" name="detail"></textarea>
                                                </div>
                                            </div>

                                            <!-- แสดงรูป preview -->
                                            <div class="text-center">
                                                <img id="receiptPreview" src="" alt=""
                                                    style="display:none; max-width:200px; border-radius:10px; margin-top:10px;">
                                            </div>
                                        </div>
                                </div>
                                <button type="submit" class="btn btn-primary pull-right px-5">ບັນທຶກ</button>
                                <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div>
                                <h2 class="card-title ">ປະຫວັດການຈ່າຍເງິນໃຫ້ລູກຄ້າ</h2>
                            </div>
                            <div class="x_content">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class=" text-primary">
                                            <th>
                                                ລ/ດ
                                            </th>
                                            <th>
                                                ວັນທີ
                                            </th>
                                            <th>
                                                ຈຳນວນເງິນ
                                            </th>
                                            <th>
                                                ໝາຍເຫດ
                                            </th>
                                            <th>
                                                ເພີ່ມໂດຍ
                                            </th>
                                            <th>
                                                ບິນຈ່າຍເງິນ
                                            </th>
                                            <th>
                                            </th>
                                        </thead>
                                        <tbody>
                                            @foreach ($refunds as $key => $refund)
                                                <tr>
                                                    <td>
                                                        {{ $key + 1 }}
                                                    </td>
                                                    <td>
                                                        {{ date('d-m-Y', strtotime($refund->created_at)) }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($refund->amount) }} ກີບ
                                                    </td>
                                                    <td>
                                                        {{ $refund->detail }}
                                                    </td>
                                                    <td>
                                                        {{ $refund->user_name }}
                                                    </td>
                                                    <td>
                                                        @if ($refund->receipt_image)
                                                            <img src="{{ '/img/receipts/' . $refund->receipt_image }}"
                                                                alt="Receipt Image"
                                                                style="max-width: 100px; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.2);">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="/editRefund/{{ $refund->id }}">
                                                            <i class="material-icons">create</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
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
            'input[name="amount"]',
            function() {
                formatIntegerInput(this);
            }
        );

        $(document).ready(function() {
            formatIntegerInput($('input[name="amount"]')[0]);
        });

        // 🚀 ลบ comma ก่อนส่งฟอร์ม (กัน backend อ่านค่าผิด)
        $('form').on('submit', function() {
            $('input[name="amount"]').val($('input[name="amount"]').val().replace(/,/g, ''));
        });


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
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                label.textContent = 'Choose file';
                preview.style.display = 'none';
                preview.src = '';
            }
        }
    </script>

    </div>
@endsection
