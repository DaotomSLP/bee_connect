@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Receive</h3>
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
            @elseif(session()->get( 'error' )=='insert_success')
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                    <span>
                        <b> Success - </b>ບັນທຶກຂໍ້ມູນສຳເລັດ</span>
                </div>
            @endif


            <div class="clearfix"></div>
            <div class="row">
                <div class="col">
                    <form method="POST" action="/addImportProductTh">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="x_panel">
                                    <div class="x_content">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Code</label>
                                                    <input class="form-control form-control-sm" name="code">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Box Name</label>
                                                    <input class="form-control form-control-sm" name="name">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Description</label>
                                                    <textarea class="form-control" name="detail"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="row">
                            <div class="col">
                                <div class="x_panel">
                                    <div>
                                        <h2>Select Destination</h2>
                                    </div>
                                    <div class="x-content">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Province</label>
                                                    <select class="form-control form-control-sm" id="select_province"
                                                        required>
                                                        <option value="">
                                                            Select
                                                        </option>
                                                        @foreach ($provinces as $province)
                                                            <option value="{{ $province->id }}">
                                                                {{ $province->prov_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Province</label>
                                                    <select class="form-control form-control-sm" disabled
                                                        id="select_district" required>
                                                        <option value="">
                                                            Select
                                                        </option>
                                                        @foreach ($districts as $district)
                                                            <option value="{{ $district->id }}">
                                                                {{ $district->dist_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="bmd-label-floating">Branch</label>
                                                    <select class="form-control form-control-sm" disabled id="select_branch"
                                                        name="receiver_branch_id" required>
                                                        <option value="">
                                                            Select
                                                        </option>
                                                        @foreach ($branchs as $branch)
                                                            <option value="{{ $branch->id }}">
                                                                {{ $branch->branch_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary pull-right px-5">Save</button>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="GET" action="/deleteImportItemThForWaiting">
                        <div class="modal-content">
                            <div>
                                <h2 class="text-center" id="exampleModalLabel"><i
                                    class="material-icons h1">delete_forever</i><br>Do you want to delete ?</h2>
                            </div>

                            <input type="hidden" id="import_id" name="id">

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Ok</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"
                                    aria-label="Close">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <hr>
            <div class="row">
                <div class="col">
                    <div class="x_panel">
                        <div>
                            <h2>Search</h2>
                        </div>
                        <div class="x_content">
                            <form method="GET" action="/addImportTh">
                                {{-- @csrf --}}
                                <input type="hidden" value="{{ Request::input('id') }}" name="id">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Code</label>
                                            <input class="form-control form-control-sm"
                                                value="{{ Request::input('product_id') }}" name="product_id">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Status</label>
                                            <select class="form-control form-control-sm" id="select_status" name="status">
                                                <option value="">
                                                    Select
                                                </option>
                                                <option {{ Request::input('status') == 'waiting' ? 'selected' : '' }}
                                                    value="waiting">
                                                    Sending to Laos
                                                </option>
                                                <option {{ Request::input('status') == 'sending' ? 'selected' : '' }}
                                                    value="sending">
                                                    Sending to Branch
                                                </option>
                                                <option {{ Request::input('status') == 'received' ? 'selected' : '' }}
                                                    value="received">
                                                    Received in Branch
                                                </option>
                                                <option {{ Request::input('status') == 'success' ? 'selected' : '' }}
                                                    value="success">
                                                    Completed
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Receive Branch</label>
                                            <select class="form-control form-control-sm" id="select_branch"
                                                name="receive_branch">
                                                <option value="">
                                                    Select
                                                </option>
                                                @foreach ($branchs as $branch)
                                                    <option
                                                        {{ Request::input('receive_branch') == $branch->id ? 'selected' : '' }}
                                                        value="{{ $branch->id }}">
                                                        {{ $branch->branch_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Receive Date</label>
                                            <input class="form-control form-control-sm" type="date"
                                                value="{{ Request::input('send_date') }}" name="send_date">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary pull-right px-4">Search</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h5 class="card-title ">Receive List</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class=" text-primary">
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            Code
                                        </th>
                                        <th>
                                            Box Name
                                        </th>
                                        <th>
                                            Receive Date
                                        </th>
                                        <th>
                                            Receive Branch
                                        </th>
                                        <th>
                                            Sale Date
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>

                                        </th>
                                    </thead>
                                    <tbody>
                                        @foreach ($import_products as $key => $import_product)
                                            <tr>
                                                <td>
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $import_product->code }}
                                                </td>
                                                <td>
                                                    {{ $import_product->name }}
                                                </td>

                                                <td>
                                                    {{$import_product->created_at}}
                                                </td>
                                                <td>
                                                    {{ $import_product->branch_name }}
                                                </td>
                                                <td>
                                                    <?php 
                                                        $date = date_create(date('d-m-Y-H:i', strtotime($import_product->success_at)), timezone_open('Pacific/Nauru'));
                                                        date_timezone_set($date, timezone_open('Asia/Vientiane'));
                                                        echo($import_product->success_at ? date_format($date,'d-m-Y-H:i') : "");
                                                    ?>
                                                </td>
                                                <td>
                                                    {{ $import_product->status == 'sending' ? 'Sending to Branch' : ($import_product->status == 'received' ? 'Received in Branch' : ($import_product->status == 'waiting' ? 'Sending to Laos' : 'Completed')) }}
                                                </td>
                                                {{-- <td>
                                            {{ number_format($import_product->total_real_price) }}
                                        </td> --}}
                                                <td>
                                                    @if ($import_product->status == 'waiting')
                                                        <a type="button" onclick="deleteLot({{ $import_product->id }})"
                                                            data-toggle="modal" data-target="#delete_modal">
                                                            <i class="material-icons">delete_forever</i>
                                                        </a>
                                                        <a href="/addImportThPdf/{{ $import_product->id }}"
                                                            target="_blank">
                                                            <i class="material-icons">print</i>
                                                        </a>
                                                    @endif
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
                            href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page={{ $pagination['offset'] - 1 }}"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item {{ $pagination['offset'] == '1' ? 'active' : '' }}">
                        <a class="page-link"
                            href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page=1">1</a>
                    </li>
                    @for ($j = $pagination['offset'] - 25; $j < $pagination['offset'] - 10; $j++) @if ($j % 10 == 0 && $j > 1) <li class="page-item
                        {{ $pagination['offset'] == $j ? 'active' : '' }}">
                        <a class="page-link"
                        href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page={{ $j }}">{{ $j }}</a>
                        </li>
                    @else @endif
                    @endfor
                    @for ($i = $pagination['offset'] - 4; $i <= $pagination['offset'] + 4 && $i <= $pagination['offsets']; $i++)
                        @if ($i > 1 && $i <= $pagination['all'])
                            <li class="page-item {{ $pagination['offset'] == $i ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page={{ $i }}">{{ $i }}</a>
                            </li>
                        @else

                        @endif
                    @endfor
                    @for ($j = $pagination['offset'] + 5; $j <= $pagination['offset'] + 20 && $j <= $pagination['offsets']; $j++)
                        @if ($j % 10 == 0 && $j > 1) <li class="page-item
                        {{ $pagination['offset'] == $j ? 'active' : '' }}">
                        <a class="page-link"
                        href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page={{ $j }}">{{ $j }}</a>
                        </li>
                    @else @endif
                    @endfor
                    <li class="page-item {{ $pagination['offset'] == $pagination['offsets'] ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ Request::route()->getName() }}?id={{ Request::input('id') }}&status={{ Request::input('status') }}&receive_branch={{ Request::input('receive_branch') }}&send_date={{ Request::input('send_date') }}&page={{ $pagination['offset'] + 1 }}"
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
    <script>
        var district_lists = <?php echo json_encode($districts); ?>;;
        var branch_lists = <?php echo json_encode($branchs); ?>;;
        $("#select_province").on("change", function() {
            let province_id = this.value;
            let district_options = "<option value=''>ເລືອກ</option>";
            district_lists
                .filter(district => district.prov_id === province_id)
                .forEach(district => {
                    district_options +=
                        `<option value="${district.id}">${district.dist_name}</option>`
                });
            $("#select_district").html(district_options)
            $("#select_district").attr("disabled", false);
            $("#select_branch").val("");
            $("#select_branch").attr("disabled", true);
        });

        $("#select_district").on("change", function() {
            let district_id = this.value;
            let branch_options = "<option value=''>ເລືອກ</option>";
            branch_lists
                .filter(branch => branch.district_id === district_id)
                .forEach(branch => {
                    branch_options +=
                        `<option value="${branch.id}">${branch.branch_name}</option>`
                });
            $("#select_branch").html(branch_options)
            $("#select_branch").attr("disabled", false);
        });

        $(document).ready(function() {
            var product_id =
                "<?php echo session()->get('id') ? session()->get('id') : 'no_id'; ?>";

            if (product_id != 'no_id') {
                window.open(`addImportThPdf/${product_id}`);
            }
        });

        function deleteLot(id) {
            $("#import_id").val(id);
        }
    </script>


@endsection
