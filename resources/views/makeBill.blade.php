@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>ການສ້າງບິນລວມ</h3>
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
            @elseif(session()->get('error') == 'delete_success')
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                    <span>
                        <b> Success - </b>ລົບຂໍ້ມູນສຳເລັດ</span>
                </div>
            @endif

            <!-- Modal -->
            <div class="modal fade" id="paid_lot_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="POST" action="/payBill" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <h2 class="mb-3">
                                    <i class="material-icons h1">paid</i><br>
                                    ຕ້ອງການຈ່າຍເງິນໃຫ້ກັບລາຍການນີ້ ຫຼືບໍ່?
                                </h2>

                                <!-- อัปโหลดใบเสร็จ -->
                                <div class="custom-file mt-3 mb-3">
                                    <input type="file" class="custom-file-input" name="receipt" id="thumbnailInput"
                                        accept="image/*" onchange="previewReceipt(event)">
                                    <label class="custom-file-label" for="thumbnailInput" id="fileLabel">Choose
                                        file</label>
                                </div>

                                <!-- แสดงรูป preview -->
                                <div class="text-center">
                                    <img id="receiptPreview" src="" alt=""
                                        style="display:none; max-width:100%; border-radius:10px; margin-top:10px;">
                                </div>

                                <input type="hidden" id="branch_id" name="branch_id">
                                <input type="hidden" id="delivery_round_id" name="delivery_round_id">
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

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title">ຄົ້ນຫາ</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="/makeBill">
                                {{-- @csrf --}}
                                <div class="row">
                                    @if (Auth::user()->is_admin == 1)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ສົ່ງໄປສາຂາ</label>
                                                <select class="form-control" id="select_branch" name="receive_branch_id"
                                                    required>
                                                    <option value="">
                                                        ເລືອກ
                                                    </option>
                                                    @foreach ($branchs as $branch)
                                                        <option
                                                            {{ Request::input('receive_branch_id') == $branch->id ? 'selected' : '' }}
                                                            value="{{ $branch->id }}">
                                                            {{ $branch->branch_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ຖ້ຽວລົດ</label>
                                                <select class="form-control" id="select_delivery_round"
                                                    name="delivery_round_id" required>
                                                    <option value="">
                                                        ເລືອກ
                                                    </option>
                                                    @foreach ($delivery_rounds as $delivery_round)
                                                        <option
                                                            {{ Request::input('delivery_round_id') == $delivery_round->id ? 'selected' : '' }}
                                                            value="{{ $delivery_round->id }}">
                                                            ຖ້ຽວທີ່ {{ $delivery_round->round }} ເດືອນ
                                                            {{ $delivery_round->month }} ລົດວັນທີ່
                                                            {{ $delivery_round->departure_time }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-sm btn-info pull-right px-4">ຄົ້ນຫາ</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <form method="GET" action="/printBill" id="insertBillForm" onsubmit="return validateCheckbox()">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title">ສ້າງບິນລວມ</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="font-weight-bold">
                                            <th>
                                                ລ/ດ
                                            </th>
                                            <th>ລາຍການສິນຄ້າ</th>
                                            <th class="text-center">ນ້ຳໜັກ</th>
                                            <th class="text-center">ຂະໜາດ</th>
                                            <th class="text-right">ລາຄາ</th>
                                            <th class="text-right">ລວມ</th>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sum_weight_kg = 0;
                                                $sum_weight_m = 0;
                                                $grand_total = 0;
                                            @endphp
                                            @foreach ($large_import_products as $key => $large_import_product)
                                                @php
                                                    if ($large_import_product->weight_type == 'kg') {
                                                        $sum_weight_kg += $large_import_product->weight;
                                                    } else {
                                                        $sum_weight_m += $large_import_product->weight;
                                                    }
                                                    $grand_total +=
                                                        $large_import_product->real_price *
                                                        $large_import_product->weight;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{ $key + 1 }}
                                                    </td>
                                                    <td>
                                                        {{ $large_import_product->code }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($large_import_product->weight_type == 'kg')
                                                            {{ $large_import_product->weight }}
                                                            {{ $large_import_product->weight_type }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($large_import_product->weight_type == 'm')
                                                            {{ $large_import_product->weight }}
                                                            {{ $large_import_product->weight_type }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($large_import_product->real_price) }}
                                                        ກີບ/{{ $large_import_product->weight_type }}</td>
                                                    <td class="text-right">
                                                        {{ number_format($large_import_product->real_price * $large_import_product->weight) }}
                                                        ກີບ
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @foreach ($normal_import_products as $key => $normal_import_product)
                                                @php
                                                    $sum_weight_kg += $normal_import_product->weight;
                                                    $grand_total +=
                                                        $normal_import_product->lot_base_price_kg *
                                                        $normal_import_product->weight;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{ sizeof($large_import_products) + $key + 1 }}
                                                    </td>
                                                    <td>
                                                        ລວມເຄື່ອງນ້ອຍ
                                                    </td>
                                                    <td class="text-center">{{ $normal_import_product->weight }} kg</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-right">
                                                        {{ number_format($normal_import_product->lot_base_price_kg) }}
                                                        ກີບ/kg</td>
                                                    <td class="text-right">
                                                        {{ number_format($normal_import_product->lot_base_price_kg * $normal_import_product->weight) }}
                                                        ກີບ
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="2"></td>
                                                <td style="text-align: center;">
                                                    {{ $sum_weight_kg }} kg
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $sum_weight_m }} m
                                                </td>
                                                <td style="text-align: right; font-weight: bold;">ລວມຄ່າເຄື່ອງ
                                                </td>
                                                <td class="text-right" style="font-weight: bold;">
                                                    {{ number_format($grand_total) }}
                                                    ກີບ
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td style="text-align: right; font-weight: bold;">ຄ່າສົ່ງ
                                                </td>
                                                <td class="text-right" style="font-weight: bold;">
                                                    {{ isset($sum_delivery_fee) ? number_format($sum_delivery_fee->fee) : 0 }}
                                                    ກີບ
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td style="text-align: right; font-weight: bold;">ຄ່າເປົາ
                                                </td>
                                                <td class="text-right" style="font-weight: bold;">
                                                    {{ isset($sum_pack_fee) ? number_format($sum_pack_fee->pack_price) : 0 }}
                                                    ກີບ
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td style="text-align: right; font-weight: bold;">ບໍລິການອື່ນໆ
                                                </td>
                                                <td class="text-right" style="font-weight: bold;">
                                                    {{ isset($sum_service_charge) ? number_format($sum_service_charge->service_charge) : 0 }}
                                                    ກີບ
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td style="text-align: right; font-weight: bold; font-size: 13pt">ລວມທັງໝົດ
                                                </td>
                                                <td class="text-right" style="font-weight: bold; font-size: 13pt">
                                                    {{ number_format(
                                                        $grand_total +
                                                            (isset($sum_delivery_fee) ? $sum_delivery_fee->fee : 0) +
                                                            (isset($sum_pack_fee) ? $sum_pack_fee->pack_price : 0) +
                                                            (isset($sum_service_charge) ? $sum_service_charge->service_charge : 0),
                                                    ) }}
                                                    ກີບ
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                @if ($receive_branch_id !== null || $delivery_round_id !== null)
                                    <a href="{{ url('printBill?receive_branch_id=' . $receive_branch_id . '&delivery_round_id=' . $delivery_round_id) }}"
                                        role="button" target="_blank" class="btn btn-primary text-white px-5">
                                        ພິມບິນ
                                    </a>

                                    @if ($bill === null)
                                        <a type="button" class="btn btn-info text-white"
                                            onclick="paidLot({{ $receive_branch_id }}, {{ $delivery_round_id }})"
                                            data-toggle="modal" data-target="#paid_lot_modal">
                                            ຈ່າຍເງິນ
                                        </a>
                                    @endif
                                @endif
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        function validateCheckbox() {
            const checkboxes = document.querySelectorAll('input[name="lot_ids[]"]:checked');
            const errorMessage = document.getElementById('error-message');

            if (checkboxes.length === 0) {
                errorMessage.style.display = 'block'; // แสดงข้อความเตือน
                return false; // ป้องกันการส่งฟอร์ม
            } else {
                errorMessage.style.display = 'none'; // ซ่อนข้อความเมื่อเลือกแล้ว
                return true; // อนุญาตให้ส่งฟอร์ม
            }
        }

        function paidLot(branch_id, delivery_round_id) {
            $("#branch_id").val(branch_id);
            $("#delivery_round_id").val(delivery_round_id);
        }
    </script>

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
@endsection
