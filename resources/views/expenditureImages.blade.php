@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <a href="/expenditure">
                        <i class="material-icons">keyboard_backspace</i>
                    </a>
                    <h3 class="d-inline pl-3">
                        ລາຍຈ່າຍ
                    </h3>
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
            @elseif(session()->get('error') == 'delete_success')
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                    <span>
                        <b> Success - </b>ລົບຂໍ້ມູນສຳເລັດ</span>
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
            <div class="clearfix"></div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div>
                            <h2 class="card-title">ເພີ່ມຮູບເອກະສານປະກອບລາຍຈ່າຍ</h2>
                        </div>
                        <div class="x_content">
                            <form method="POST" enctype="multipart/form-data" action="/addExpenditureImages">
                                @csrf
                                <input type="hidden" name="expen_id" value="{{ $id }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <img src="..." alt="..." id="slide-img" class="img-thumbnail">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="file" class="custom-file-input" name="img_src"
                                                id="thumbnailInput">
                                            <label class="custom-file-label" for="thumbnailInput">Choose file</label>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div>
                            <h2>ເອກະສານປະກອບລາຍຈ່າຍ</h2>
                        </div>
                        <div class="x_content">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class=" text-primary">
                                        <th>

                                        </th>
                                        <th>

                                        </th>
                                    </thead>
                                    <tbody>
                                        @foreach ($expenditureImages as $expenditureImage)
                                            <tr>
                                                <td class="w-50">
                                                    <img src="/img/design/slide/{{ $expenditureImage->img_src }}"
                                                        alt="..." class="img-fluid img-thumbnail">
                                                </td>
                                                <td>
                                                    <a class="pl-5"
                                                        href="/deleteExpenditureImages/{{ $expenditureImage->id }}/expen/{{ $id }}">
                                                        <i class="material-icons">delete</i>
                                                    </a>
                                                </td>
                                                {{-- <td>
                                                    <a href="/deletePlan/{{ $expenditureImage->id }}">
                                                        {{ $expenditureImage->enabled == '1' ? 'ປິດໃຊ້ງານ' : 'ເປີດໃຊ້ງານ' }}
                                                    </a>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);

            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    console.log(event.target.result);
                    $('#slide-img').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
