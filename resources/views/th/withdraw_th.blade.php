@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        {{-- <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>ລາຍງານຜົນໄດ້ຮັບ</h3>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr>
            <div class="row">
                <div class="col-12 col-mg-4 col-lg-4">
                    <div class="x_panel">
                        <div>
                            <p class="h4">ຈຳນວນເງິນທີ່ໄດ້ຮັບ</p>
                        </div>
                        <hr>
                        <div class="x_content">
                            <p class="h2">{{ number_format($sum_income) }} ບາດ</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="x_panel">
            <div>
                <h2 class="card-title ">ເພີ່ມການເບີກເງິນ</h2>
            </div>
            <div class="x_content">
                <form method="POST" action="/addWithDrawTh">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="bmd-label-floating">ຫຸ້ນສ່ວນ</label>
                                <select class="form-control form-control-sm" name="partner" required>
                                    <option value="">
                                        Select
                                    </option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }} {{ $user->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="bmd-label-floating">ຈຳນວນເງິນ</label>
                                <input class="form-control form-control-sm" name="price">
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary pull-right px-5">Save</button>
                            <div class="clearfix"></div>
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
                        <h2 class="card-title ">ລາຍການເບີກເງິນ</h2>
                    </div>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                    <th>
                                        ລ/ດ
                                    </th>
                                    <th>
                                        ຊື່ ແລະ ນາມສະກຸນ
                                    </th>
                                    <th>
                                        ຈຳນວນເງິນ
                                    </th>
                                    <th>
                                        ວັນທີ
                                    </th>
                                </thead>
                                <tbody>
                                    @foreach ($withdraws as $key => $withdraw)
                                        <tr>
                                            <td>
                                                {{ $key + 1 }}
                                            </td>
                                            <td>
                                                {{ $withdraw->name }} {{ $withdraw->last_name }}
                                            </td>
                                            <td>
                                                {{ $withdraw->price }}
                                            </td>
                                            <td>
                                                <?php
                                                $date = date_create(date('d-m-Y-H:i', strtotime($withdraw->created_at)), timezone_open('Pacific/Nauru'));
                                                date_timezone_set($date, timezone_open('Asia/Vientiane'));
                                                echo $withdraw->created_at ? date_format($date, 'd-m-Y-H:i') : '';
                                                ?>
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
                        href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $pagination['offset'] - 1 }}"
                        aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <li class="page-item {{ $pagination['offset'] == '1' ? 'active' : '' }}">
                    <a class="page-link"
                        href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page=1">1</a>
                </li>
                @for ($j = $pagination['offset'] - 25; $j < $pagination['offset'] - 10; $j++)
                    @if ($j % 10 == 0 && $j > 1) <li class="page-item
                    {{ $pagination['offset'] == $j ? 'active' : '' }}">
                    <a class="page-link"
                    href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $j }}">{{ $j }}</a>
                    </li>
                @else @endif
                @endfor
                @for ($i = $pagination['offset'] - 4; $i <= $pagination['offset'] + 4 && $i <= $pagination['offsets']; $i++)
                    @if ($i > 1 && $i <= $pagination['all'])
                        <li class="page-item {{ $pagination['offset'] == $i ? 'active' : '' }}">
                            <a class="page-link"
                                href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $i }}">{{ $i }}</a>
                        </li>
                    @else

                    @endif
                @endfor
                @for ($j = $pagination['offset'] + 5; $j <= $pagination['offset'] + 20 && $j <= $pagination['offsets']; $j++)
                    @if ($j % 10 == 0 && $j > 1) <li class="page-item
                    {{ $pagination['offset'] == $j ? 'active' : '' }}">
                    <a class="page-link"
                    href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $j }}">{{ $j }}</a>
                    </li>
                @else @endif
                @endfor
                <li class="page-item {{ $pagination['offset'] == $pagination['offsets'] ? 'disabled' : '' }}">
                    <a class="page-link"
                        href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $pagination['offset'] + 1 }}"
                        aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    </div>
@endsection
