    @extends('layout')

    @section('body')
        <!-- End Navbar -->
        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>ລາຍງານການຈ່າຍຄ່າເຄື່ອງເສຍ</h3>
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr>
                <div class="row">
                    <div class="col">
                        <h5>ປະຈຳວັນທີ :</h5>
                        <form method="GET" action="refund-report">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ເລືອກວັນທີ່</label>
                                        <input class="form-control" type="date" name="startDate"
                                            value="{{ isset($startDate) ? date('Y-m-d', strtotime($startDate)) : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-1" style="justify-items: center; align-content: center;">
                                    <p>

                                        <i class="material-icons">arrow_forward</i>
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ເລືອກວັນທີ່</label>
                                        <input class="form-control" type="date" name="endDate"
                                            value="{{ isset($endDate) ? date('Y-m-d', strtotime($endDate)) : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary px-5 mt-4">ຄົ້ນຫາ</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div>
                                <h2 class="card-title ">ລາຍງານການຈ່າຍຄ່າເຄື່ອງເສຍ</h2>
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
                                                ລະຫັດເຄື່ອງ
                                            </th>
                                            <th>
                                                ໝາຍເຫດ
                                            </th>
                                            <th>
                                                ເຄື່ອງວັນທີ
                                            </th>
                                            <th>
                                                ຈາກສາຂາ
                                            </th>
                                            <th>
                                                ບິນຈ່າຍເງິນ
                                            </th>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sumRefund = 0;
                                            @endphp
                                            @foreach ($refunds as $key => $refund)
                                                @php
                                                    $sumRefund += $refund->amount;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{ $key + 1 }}
                                                    </td>
                                                    <td>
                                                        {{ date('d-m-Y', strtotime($refund->created_at)) }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($refund->amount) }}
                                                        ກີບ
                                                    </td>
                                                    <td>
                                                        {{ $refund->parcel_code }}
                                                    </td>
                                                    <td>
                                                        {{ $refund->detail }}
                                                    </td>
                                                    <td>
                                                        {{ date('d-m-Y', strtotime($refund->received_at)) }}
                                                    </td>
                                                    <td>
                                                        {{ $refund->branch_name }}
                                                    </td>
                                                    <td>
                                                        @if ($refund->receipt_image)
                                                            <img src="{{ '/img/receipts/' . $refund->receipt_image }}"
                                                                alt="Receipt Image"
                                                                style="max-width: 100px; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.2);">
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="2">
                                                    <p class="text-center h6 font-weight-bold">
                                                        ລວມ :
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="h6 font-weight-bold">
                                                        {{ number_format($sumRefund) }}
                                                        ກີບ
                                                    </p>
                                                </td>
                                                <td colspan="5">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_content">
                                <div>
                                    <a href="{{ url('refund-report-print?startDate=' . $startDate . '&endDate=' . $endDate) }}"
                                        target="_blank"
                                        class="btn btn-primary px-5 {{ isset($startDate) && isset($endDate) ? '' : 'disabled-link' }}">
                                        ພິມລາຍງານ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- jQuery -->
        <script src="../vendors/jquery/dist/jquery.min.js"></script>
        <!-- Chart.js -->
        <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
    @endsection
