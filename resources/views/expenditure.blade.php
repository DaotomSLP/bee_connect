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
                                <h2 class="card-title">ເພິ່ມລາຍຈ່າຍ</h2>
                            </div>
                            <div class="x_content">
                                <form method="POST" action="/addExpenditure" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ວັນທີ</label>
                                                <input class="form-control" type="date" value="{{ date('Y-m-d') }}"
                                                    name="date">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ຈຳນວນເງິນ</label>
                                                <input class="form-control" name="price">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ລາຍລະອຽດ</label>
                                                <textarea class="form-control" name="detail"></textarea>
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
                                                            {{ $key == '0' ? 'selected' : '' }}>
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
                                                <img id="receiptPreview" src="" alt=""
                                                    style="display:none; max-width:50%; border-radius:10px; margin-top:10px;">
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

            <div class="row">
                <div class="col">
                    <form method="GET" action="/expenditure" class="mb-0">
                        {{-- @csrf --}}
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="bmd-label-floating">ເລືອກຖ້ຽວລົດ</label>
                                    <select class="form-control form-control-md" id="select_delivery_round"
                                        name="delivery_round_id" required>
                                        <option value="">
                                            ເລືອກ
                                        </option>
                                        @foreach ($delivery_rounds as $key => $delivery_round)
                                            <option value="{{ $delivery_round->id }}"
                                                {{ Request::input('delivery_round_id') == $delivery_round->id ? 'selected' : ($key == '0' ? 'selected' : '') }}>
                                                ຖ້ຽວທີ່ {{ $delivery_round->round }} ເດືອນ
                                                {{ $delivery_round->month }} ລົດວັນທີ່
                                                {{ $delivery_round->departure_time }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label class="bmd-label-floating"></label>
                                    <button type="submit"
                                        class="btn btn-primary pull-right px-5 form-control">ຄົ້ນຫາ</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div>
                            <h2 class="card-title ">ລາຍການລາຍຈ່າຍ
                                <button type="button"
                                    onclick="window.open(`expenditureReport?delivery_round_id={{ Request::input('delivery_round_id') }}`);"
                                    class="btn btn-primary ml-3 px-3"
                                    {{ Request::input('delivery_round_id') ? '' : 'disabled' }}>ພິມລາຍງານ</button>
                            </h2>
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
                                            ຖ້ຽວລົດ
                                        </th>
                                        <th>
                                            ຈຳນວນເງິນ
                                        </th>
                                        <th>
                                            ລາຍລະອຽດ
                                        </th>
                                        <th>
                                            ເພີ່ມໂດຍ
                                        </th>
                                        <th>
                                            ເອກະສານປະກອບ
                                        </th>
                                        <th>
                                        </th>
                                    </thead>
                                    <tbody>
                                        @foreach ($expenditure as $key => $expen)
                                            <tr>
                                                <td>
                                                    {{ $pagination['offset'] ? ($pagination['offset'] - 1) * 25 + $key + 1 : $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ date('d-m-Y', strtotime($expen->created_at)) }}
                                                </td>
                                                <td>
                                                    ຖ້ຽວທີ່ {{ $expen->round }} ເດືອນ
                                                    {{ $expen->month }} ລົດວັນທີ່
                                                    {{ $expen->departure_time }}
                                                </td>
                                                <td>
                                                    {{ number_format($expen->price) }} ກີບ
                                                </td>
                                                <td>
                                                    {{ $expen->detail }}
                                                </td>
                                                <td>
                                                    {{ $expen->name }}
                                                </td>
                                                <td>
                                                    @if ($expen->receipt_image)
                                                        <img src="{{ '/img/receipts/' . $expen->receipt_image }}"
                                                            alt="Receipt Image"
                                                            style="max-width: 100px; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.2);">
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="/editExpenditure/{{ $expen->id }}">
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

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $pagination['offset'] == 1 ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ Request::route()->getName() }}?delivery_round_id={{ Request::input('delivery_round_id') }}&page={{ $pagination['offset'] - 1 }}"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item {{ $pagination['offset'] == '1' ? 'active' : '' }}">
                        <a class="page-link"
                            href="{{ Request::route()->getName() }}?delivery_round_id={{ Request::input('delivery_round_id') }}&page=1">1</a>
                    </li>
                    @for ($j = $pagination['offset'] - 25; $j < $pagination['offset'] - 10; $j++)
                        @if ($j % 10 == 0 && $j > 1)
                            <li
                                class="page-item
                        {{ $pagination['offset'] == $j ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?delivery_round_id={{ Request::input('delivery_round_id') }}&page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else
                        @endif
                    @endfor
                    @for ($i = $pagination['offset'] - 4; $i <= $pagination['offset'] + 4 && $i <= $pagination['offsets']; $i++)
                        @if ($i > 1 && $i <= $pagination['all'])
                            <li class="page-item {{ $pagination['offset'] == $i ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?delivery_round_id={{ Request::input('delivery_round_id') }}&page={{ $i }}">{{ $i }}</a>
                            </li>
                        @else
                        @endif
                    @endfor
                    @for ($j = $pagination['offset'] + 5; $j <= $pagination['offset'] + 20 && $j <= $pagination['offsets']; $j++)
                        @if ($j % 10 == 0 && $j > 1)
                            <li
                                class="page-item
                        {{ $pagination['offset'] == $j ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?delivery_round_id={{ Request::input('delivery_round_id') }}&page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else
                        @endif
                    @endfor
                    <li class="page-item {{ $pagination['offset'] == $pagination['offsets'] ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ Request::route()->getName() }}?delivery_round_id={{ Request::input('delivery_round_id') }}&page={{ $pagination['offset'] + 1 }}"
                            aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
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
