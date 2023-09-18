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
                                <form method="POST" action="/addExpenditure">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">ວັນທີ</label>
                                                <input class="form-control" type="date" value="{{ $date_now }}"
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
                            <div class="col-8">
                                <div class="form-group">
                                    <label class="bmd-label-floating">ຄົ້ນຫາຕາມວັນທີ່</label>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-12">
                                            <div class="form-group">
                                                <input class="form-control" type="date" value="{{ $date_now }}"
                                                    name="date">
                                            </div>
                                        </div>
                                        <p class="h5">ຫາ</p>
                                        <div class="col-lg-4 col-md-4 col-12">
                                            <div class="form-group">
                                                <input class="form-control" type="date" value="{{ $to_date_now }}"
                                                    name="to_date">
                                            </div>
                                        </div>
                                    </div>
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
                                    onclick="window.open(`expenditureReport?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}`);"
                                    class="btn btn-primary ml-3 px-3" {{ Request::input('date') ? '' : 'disabled' }}>ພິມລາຍງານ</button>
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
                                                    {{ number_format($expen->price) }} ກີບ
                                                </td>
                                                <td>
                                                    {{ $expen->detail }}
                                                </td>
                                                <td>
                                                    {{ $expen->name }}
                                                </td>
                                                <td>
                                                    <a href="/expenditureImages/{{ $expen->id }}">
                                                        <i class="material-icons">image</i>
                                                    </a>
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
                        @if ($j % 10 == 0 && $j > 1)
                            <li
                                class="page-item
                        {{ $pagination['offset'] == $j ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else
                        @endif
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
                        @if ($j % 10 == 0 && $j > 1)
                            <li
                                class="page-item
                        {{ $pagination['offset'] == $j ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?date={{ Request::input('date') }}&to_date={{ Request::input('to_date') }}&page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else
                        @endif
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
