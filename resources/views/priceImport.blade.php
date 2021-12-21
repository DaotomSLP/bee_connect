@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>ຕັ້ງຄ່າລາຄາເຄື່ອງນຳເຂົ້າ</h3>
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

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title">ຕັ້ງຄ່າລາຄາເຄື່ອງນຳເຂົ້າ</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/addPriceImport">
                                @csrf
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ຕົ້ນທຶນ/ຫົວໜ່ວຍ</label>
                                            <input type="number" min="100" class="form-control" name="base_price"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ຄ່າສົ່ງ/ຫົວໜ່ວຍ</label>
                                            <input type="number" min="100" class="form-control" name="real_price"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ຫົວໜ່ວຍ</label>
                                            <select class="form-control" name="weight_type" required>
                                                <option value="">ເລືອກ</option>
                                                <option value="kg">ກິໂລກຼາມ</option>
                                                <option value="m">ແມັດກ້ອນ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-info pull-right px-5">ບັນທຶກ</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title">ຄົ້ນຫາ</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="/priceImport">
                                {{-- @csrf --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ຫົວໜ່ວຍ</label>
                                            <select class="form-control" id="unit" name="unit">
                                                <option value="">
                                                    ເລືອກ
                                                </option>
                                                <option {{ Request::input('unit') == 'm' ? 'selected' : '' }} value="m">
                                                    ແມັດກ້ອນ
                                                </option>
                                                <option {{ Request::input('unit') == 'kg' ? 'selected' : '' }} value="kg">
                                                    ກຼາມ
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ວັນທີ</label>
                                            <input class="form-control" type="date" value="{{ Request::input('date') }}"
                                                name="date">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-info pull-right px-5">ຄົ້ນຫາ</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title">ປະຫວັດການຕັ້ງຄ່າລາຄາ</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead class="font-weight-bold">
                                    <th>
                                        ປະຈຳວັນທີ
                                    </th>
                                    <th>
                                        ຕົ້ນທຶນ
                                    </th>
                                    <th>
                                        ຄ່າສົ່ງ
                                    </th>
                                    <th>
                                        ຕໍ່ຫົວໜ່ວຍ
                                    </th>
                                </thead>
                                <tbody>
                                    @foreach ($prices as $price)
                                        <tr>
                                            <td>
                                                {{ date('d-m-Y', strtotime($price->created_at)) }}
                                            </td>
                                            <td>
                                                {{ $price->base_price }}
                                            </td>
                                            <td>
                                                {{ $price->real_price }}
                                            </td>
                                            <td>
                                                {{ $price->weight_type == 'm' ? 'ແມັດກ້ອນ' : 'ກຼາມ' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $pagination['offset'] == 1 ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ Request::route()->getName() }}?unit={{ Request::input('unit') }}&date={{ Request::input('date') }}&page={{ $pagination['offset'] - 1 }}"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item {{ $pagination['offset'] == '1' ? 'active' : '' }}">
                        <a class="page-link"
                            href="{{ Request::route()->getName() }}?unit={{ Request::input('unit') }}&date={{ Request::input('date') }}&page=1">1</a>
                    </li>
                    @for ($j = $pagination['offset'] - 25; $j < $pagination['offset'] - 10; $j++)
                        @if ($j % 10 == 0 && $j > 1)
                            <li
                                class="page-item
                        {{ $pagination['offset'] == $j ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?unit={{ Request::input('unit') }}&date={{ Request::input('date') }}&page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else
                        @endif
                    @endfor
                    @for ($i = $pagination['offset'] - 4; $i <= $pagination['offset'] + 4 && $i <= $pagination['offsets']; $i++)
                        @if ($i > 1 && $i <= $pagination['all'])
                            <li class="page-item {{ $pagination['offset'] == $i ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?unit={{ Request::input('unit') }}&date={{ Request::input('date') }}&page={{ $i }}">{{ $i }}</a>
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
                                    href="{{ Request::route()->getName() }}?unit={{ Request::input('unit') }}&date={{ Request::input('date') }}&page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else
                        @endif
                    @endfor
                    <li class="page-item {{ $pagination['offset'] == $pagination['offsets'] ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ Request::route()->getName() }}?unit={{ Request::input('unit') }}&date={{ Request::input('date') }}&page={{ $pagination['offset'] + 1 }}"
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

    </script>
@endsection
