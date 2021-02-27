@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>ການສົ່ງສິນຄ້າພາຍໃນທັງໝົດ
                    </h3>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="clearfix"></div>
            <div class="row">
                <div class="col">
                    <div class="x_panel">
                        <div>
                            <h2>ຄົ້ນຫາ</h2>
                        </div>
                        <div class="x_content">
                            <form class="form-label-left input_mask" method="GET" action="/allProducts">
                                <div class="col-md-3 col-lg-3 col-12  form-group has-feedback">
                                    <label for="ex3" class="col-form-label">ລະຫັດເຄື່ອງ</label>
                                    <input type="text" value="{{ Request::input('id') }}" name="id" class="form-control"
                                        placeholder=" ">
                                </div>

                                <div class="col-md-3 col-lg-3 col-12  form-group has-feedback">
                                    <label for="ex3" class="col-form-label">ສະຖານະ</label>
                                    <select class="form-control" id="select_status" name="status">
                                        <option value="">
                                            ເລືອກ
                                        </option>
                                        <option {{ Request::input('status') == 'sending' ? 'selected' : '' }}
                                            value="sending">
                                            ກຳລັງສົ່ງ
                                        </option>
                                        <option {{ Request::input('status') == 'received' ? 'selected' : '' }}
                                            value="received">
                                            ຮອດແລ້ວ
                                        </option>
                                        <option {{ Request::input('status') == 'success' ? 'selected' : '' }}
                                            value="success">
                                            ສຳເລັດ
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3 col-lg-3 col-12  form-group has-feedback">
                                    <label for="ex3" class="col-form-label">ສົ່ງໄປສາຂາ</label>
                                    <select class="form-control" id="select_branch" name="receive_branch">
                                        <option value="">
                                            ເລືອກ
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

                                <div class="col-md-3 col-lg-3 col-12  form-group has-feedback">
                                    <label for="ex3" class="col-form-label">ວັນທີສົ່ງ</label>
                                    <input class="form-control" type="date" value="{{ Request::input('send_date') }}"
                                        name="send_date">
                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success">ຄົ້ນຫາ</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col">
                    <div class="x_panel">
                        <div>
                            <h2>ລາຍການຮັບສົ່ງສິນຄ້າພາຍໃນທັງໝົດຂອງສາຂາ</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <th>
                                            ລ/ດ
                                        </th>
                                        <th>
                                            ລະຫັດເຄື່ອງ
                                        </th>
                                        <th>
                                            ຂະໜາດ
                                        </th>
                                        @if (Auth::user()->is_admin == 1)
                                            <th>
                                                ຈາກສາຂາ
                                            </th>
                                        @endif
                                        <th>
                                            ສ່ົ່ງໄປສາຂາ
                                        </th>
                                        <th>
                                            ສົ່ງວັນທີ
                                        </th>
                                        <th>
                                            ລາຄາ
                                        </th>
                                        <th>
                                            ສ່ວນແບ່ງ
                                        </th>
                                        <th>
                                            ລູກຄ້າຜູ້ສົ່ງ
                                        </th>
                                        <th>
                                            ເບີໂທຜູ້ສົ່ງ
                                        </th>
                                        <th>
                                            ລູກຄ້າຜູ້ຮັບ
                                        </th>
                                        <th>
                                            ເບີໂທຜູ້ຮັບ
                                        </th>
                                        <th>
                                            ສະຖານະ
                                        </th>
                                        <th>
                                            ປະເພດການຈ່າຍເງິນ
                                        </th>
                                        <th>
                                            ສະຖານະຈ່າຍເງິນ
                                        </th>
                                        <th>
                                        </th>
                                        <th>
                                        </th>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $key => $product)
                                            <tr>
                                                <td>
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $product->id }}
                                                </td>
                                                <td>
                                                    {{ $product->weight }}
                                                    {{ $product->weight_type == 'm' ? 'ແມັດກ້ອນ' : 'kg' }}
                                                </td>
                                                @if (Auth::user()->is_admin == 1)
                                                    <td>
                                                        {{ $product->sender_branch_name }}
                                                    </td>
                                                @endif
                                                <td>
                                                    {{ $product->receiver_branch_name }}
                                                </td>
                                                <td>
                                                    {{ date('d-m-Y', strtotime($product->created_at)) }}
                                                </td>
                                                <td>
                                                    {{ $product->price }} ກີບ
                                                </td>
                                                <td>
                                                    {{ ($product->price / 5) * 2 }} ກີບ
                                                </td>
                                                <td>
                                                    {{ $product->cust_send_name }}
                                                </td>
                                                <td>
                                                    {{ $product->cust_send_tel }}
                                                </td>
                                                <td>
                                                    {{ $product->cust_receiver_name }}
                                                </td>
                                                <td>
                                                    {{ $product->cust_receiver_tel }}
                                                </td>
                                                <td>
                                                    {{ $product->status == 'sending' ? 'ກຳລັງສົ່ງ' : ($product->status == 'received' ? 'ຮອດແລ້ວ' : 'ສຳເລັດ') }}
                                                </td>
                                                <td>
                                                    {{ $product->payment_type == 'normal' ? 'ທົ່ວໄປ' : 'ຈ່າຍປາຍທາງ' }}
                                                </td>
                                                <td>
                                                    @if ($product->payment_type == 'normal')
                                                        @if ($product->receiver_branch_id == Auth::user()->branch_id)
                                                            {{ $product->second_branch_payment_status == 'paid' ? 'ຈ່າຍແລ້ວ' : 'ຍັງບໍ່ຈ່າຍ' }}
                                                        @else
                                                            {{ $product->payment_status == 'paid' ? 'ຈ່າຍແລ້ວ' : 'ຍັງບໍ່ຈ່າຍ' }}
                                                        @endif
                                                    @else
                                                        @if ($product->receiver_branch_id == Auth::user()->branch_id)
                                                            {{ $product->payment_status == 'paid' ? 'ຈ່າຍແລ້ວ' : 'ຍັງບໍ່ຈ່າຍ' }}
                                                        @else
                                                            {{ $product->second_branch_payment_status == 'paid' ? 'ຈ່າຍແລ້ວ' : 'ຍັງບໍ່ຈ່າຍ' }}
                                                        @endif

                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!$product->received_at)
                                                        <a href="/pdf/{{ $product->id }}" target="_blank">
                                                            <i class="material-icons">print</i>
                                                        </a>
                                                    @endif

                                                    @if ($product->payment_type == 'normal')
                                                        @if ($product->receiver_branch_id == Auth::user()->branch_id && $product->second_branch_payment_status != 'paid')
                                                            <a href="/paidProductForSecondBranch?id={{ $product->id }}">
                                                                ຮັບເງິນ
                                                            </a>
                                                        @endif
                                                    @else
                                                        @if ($product->sender_branch_id == Auth::user()->branch_id && $product->second_branch_payment_status != 'paid')
                                                            <a href="/paidProductForSecondBranch?id={{ $product->id }}">
                                                                ຮັບເງິນ
                                                            </a>
                                                        @endif
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
                    @for ($j = $pagination['offset'] - 25; $j < $pagination['offset'] - 10; $j++)
                        @if ($j % 10 == 0 && $j > 1) <li class="page-item
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

@endsection
