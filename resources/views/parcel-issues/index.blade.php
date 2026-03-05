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
            @elseif(session()->get('error') == 'update_success')
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                    <span>
                        <b> Success - </b>ແກ້ໄຂຂໍ້ມູນສຳເລັດ</span>
                </div>
            @elseif(session()->get('error') == 'ship_success')
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                    <span>
                        <b> Success - </b>ສົ່ງເຄື່ອງສຳເລັດ</span>
                </div>
            @endif

            @if (Auth::user()->is_admin == 1)
                <div class="row">
                    <div class="col">
                        <div class="x_panel">
                            <div>
                                <h2 class="card-title">ເພິ່ມຂໍ້ມູນເຄື່ອງເສຍ</h2>
                            </div>
                            <div class="x_content">
                                <form method="POST" action="/add-parcel-issue" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ເຄື່ອງວັນທີ</label>
                                                <input class="form-control" type="date" value="{{ date('Y-m-d') }}"
                                                    name="received_at">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ລະຫັດເຄື່ອງ</label>
                                                <input class="form-control" name="parcel_code">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ຈຳນວນເງິນ</label>
                                                <input class="form-control" name="parcel_price">
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
                                                            {{ Request::input('receiver_branch_id') == $branch->id ? 'selected' : '' }}
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
                                                <textarea class="form-control" name="detail"></textarea>
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
                    <form method="GET" action="/parcel-issues" class="mb-0">
                        {{-- @csrf --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating">ລະຫັດເຄື່ອງ</label>
                                    <input class="form-control" name="parcel_code"
                                        value="{{ Request::input('parcel_code') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="bmd-label-floating">ເຄື່ອງວັນທີ</label>
                                    <input class="form-control" type="date" value="{{ Request::input('received_at') }}"
                                        name="received_at">
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
                                                {{ Request::input('receiver_branch_id') == $branch->id ? 'selected' : '' }}
                                                value="{{ $branch->id }}">
                                                {{ $branch->branch_name }}
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
                            <h2 class="card-title ">ຂໍ້ມູນເຄື່ອງເສຍ
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
                                            ລະຫັດເຄື່ອງ
                                        </th>
                                        <th>
                                            ຈຳນວນເງິນ
                                        </th>
                                        <th>
                                            ຈ່າຍເງີນໃຫ້ລູກຄ້າແລ້ວ
                                        </th>
                                        <th>
                                            ລາຍລະອຽດ
                                        </th>
                                        <th>
                                            ເຄື່ອງວັນທີ
                                        </th>
                                        <th>
                                            ເຄື່ອງຂອງສາຂາ
                                        </th>
                                        <th>
                                            ສະຖານະ
                                        </th>
                                        <th>
                                            ຫຼັກຖານ
                                        </th>
                                        <th>
                                            ເພີ່ມໂດຍ
                                        </th>
                                        <th>
                                        </th>
                                        <th>
                                        </th>
                                    </thead>
                                    <tbody>
                                        @foreach ($parcel_issues as $key => $parcel_issue)
                                            <tr>
                                                <td>
                                                    {{ $pagination['offset'] ? ($pagination['offset'] - 1) * 25 + $key + 1 : $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $parcel_issue->parcel_code }}
                                                </td>
                                                <td>
                                                    {{ number_format($parcel_issue->parcel_price) }} ກີບ
                                                </td>
                                                <td>
                                                    {{ number_format($parcel_issue->total_refund) }} ກີບ
                                                </td>
                                                <td>
                                                    {{ $parcel_issue->detail }}
                                                </td>
                                                <td>
                                                    {{ date('d-m-Y', strtotime($parcel_issue->received_at)) }}
                                                </td>
                                                <td>
                                                    {{ $parcel_issue->branch_name }}
                                                </td>
                                                <td>
                                                    @if ($parcel_issue->status == 'pending')
                                                        ກຳລັງກວດສອບ
                                                    @elseif ($parcel_issue->status == 'success')
                                                        ສົ່ງເຄື່ອງສຳເລັດ
                                                    @else
                                                        ຈ່າຍເງີນໃຫ້ລູກຄ້າ
                                                    @endif
                                                </td>

                                                </td>
                                                <td>
                                                    <a href="/parcel-issue-images/{{ $parcel_issue->id }}">
                                                        <i class="material-icons">image</i>
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ $parcel_issue->user_name }}
                                                </td>
                                                <td>
                                                    <a href="/edit-parcel-issue/{{ $parcel_issue->id }}">
                                                        <i class="material-icons">create</i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a type="button" data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="material-icons">more_vert</i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if ($parcel_issue->status == 'pending')
                                                            <a class="dropdown-item"
                                                                href="/ship-parcel-issue/{{ $parcel_issue->id }}">
                                                                ສົ່ງເຄື່ອງສຳເລັດ
                                                            </a>
                                                        @endif
                                                        <a class="dropdown-item"
                                                            href="/refund-parcel-issue/{{ $parcel_issue->id }}">
                                                            ໃຊ້ເງີນໃຫ້ລູກຄ້າ
                                                        </a>
                                                    </div>
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
                            href="{{ Request::route()->getName() }}?receiver_branch_id={{ Request::input('receiver_branch_id') }}&received_at={{ Request::input('received_at') }}&parcel_code={{ Request::input('parcel_code') }}&page={{ $pagination['offset'] - 1 }}"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item {{ $pagination['offset'] == '1' ? 'active' : '' }}">
                        <a class="page-link"
                            href="{{ Request::route()->getName() }}?receiver_branch_id={{ Request::input('receiver_branch_id') }}&received_at={{ Request::input('received_at') }}&parcel_code={{ Request::input('parcel_code') }}&page=1">1</a>
                    </li>
                    @for ($j = $pagination['offset'] - 25; $j < $pagination['offset'] - 10; $j++)
                        @if ($j % 10 == 0 && $j > 1)
                            <li
                                class="page-item
                        {{ $pagination['offset'] == $j ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?receiver_branch_id={{ Request::input('receiver_branch_id') }}&received_at={{ Request::input('received_at') }}&parcel_code={{ Request::input('parcel_code') }}&page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else
                        @endif
                    @endfor
                    @for ($i = $pagination['offset'] - 4; $i <= $pagination['offset'] + 4 && $i <= $pagination['offsets']; $i++)
                        @if ($i > 1 && $i <= $pagination['all'])
                            <li class="page-item {{ $pagination['offset'] == $i ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?receiver_branch_id={{ Request::input('receiver_branch_id') }}&received_at={{ Request::input('received_at') }}&parcel_code={{ Request::input('parcel_code') }}&page={{ $i }}">{{ $i }}</a>
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
                                    href="{{ Request::route()->getName() }}?receiver_branch_id={{ Request::input('receiver_branch_id') }}&received_at={{ Request::input('received_at') }}&parcel_code={{ Request::input('parcel_code') }}&page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else
                        @endif
                    @endfor
                    <li class="page-item {{ $pagination['offset'] == $pagination['offsets'] ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ Request::route()->getName() }}?receiver_branch_id={{ Request::input('receiver_branch_id') }}&received_at={{ Request::input('received_at') }}&parcel_code={{ Request::input('parcel_code') }}&page={{ $pagination['offset'] + 1 }}"
                            aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
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
            'input[name="parcel_price"]',
            function() {
                formatIntegerInput(this);
            }
        );

        // 🚀 ลบ comma ก่อนส่งฟอร์ม (กัน backend อ่านค่าผิด)
        $('form').on('submit', function() {
            $('input[name="parcel_price"]').val($('input[name="parcel_price"]').val().replace(/,/g, ''));
        });
    </script>
@endsection
