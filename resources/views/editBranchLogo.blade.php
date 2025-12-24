@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>ແກ້ໄຂໂລໂກ້</h3>
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
                <div class="col-md-12">
                    <div class="x_panel">
                        <div>
                            <h2 class="card-title">ແກ້ໄຂໂລໂກ້</h2>
                        </div>
                        <div class="x_content">
                            <form method="POST" action="/updateBranchLogo" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        <!-- อัปโหลดใบเสร็จ -->
                                        <div class="custom-file mt-3 mb-3">
                                            <input type="file" class="custom-file-input" name="logo"
                                                id="thumbnailInput" accept="image/*" onchange="previewReceipt(event)">
                                            <label class="custom-file-label" for="thumbnailInput" id="fileLabel">Choose
                                                file</label>
                                        </div>

                                        <!-- แสดงรูป preview -->
                                        <div class="text-center">
                                            <img id="logoPreview" src="{{ '/img/logos/' . $branch->logo_image }}"
                                                alt=""
                                                style="max-width:200px; border-radius:10px; margin-top:10px;">
                                        </div>

                                        <input type="hidden" id="bill_id_input" name="bill_id">
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">ຕົກລົງ</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                                            aria-label="Close">ຍົກເລີກ</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        function previewReceipt(event) {
            const input = event.target;
            const file = input.files[0];
            const label = document.getElementById('fileLabel');
            const preview = document.getElementById('logoPreview');

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
@endsection
