@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>ຫຼັກຖານເຄື່ອງເສຍ</h3>
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
                <div class="row">
                    <div class="col">
                        <div class="x_panel">
                            <div>
                                <h2 class="card-title">ເພີ່ມຫຼັກຖານເຄື່ອງເສຍ</h2>
                            </div>
                            <div class="x_content">
                                <form method="POST" action="/add-parcel-issue-image" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="parcel_issue_id" value="{{ $parcel_issue->id }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <!-- อัปโหลดใบเสร็จ -->
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ບິນຈ່າຍເງິນ</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" name="image"
                                                        id="thumbnailInput" accept="image/*"
                                                        onchange="previewReceipt(event)" required>
                                                    <label class="custom-file-label" for="thumbnailInput"
                                                        id="fileLabel">Choose
                                                        file</label>
                                                </div>
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
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div>
                                <h2 class="card-title ">ຫຼັກຖານເຄື່ອງເສຍ</h2>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    @foreach ($images as $key => $image)
                                        <div class="col-3">
                                            <img src="{{ '/img/receipts/' . $image->image }}" alt="Receipt Image"
                                                style="max-width: 100%; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.2);">
                                        </div>
                                    @endforeach
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
