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

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title">ເພີ່ມການເບີກເງິນປັນຜົນ</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/addWithDrawCh">
                            @csrf
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ຈຳນວນເງິນ</label>
                                        <input class="form-control form-control-sm" name="price">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-info px-5">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title">ລາຍການເບີກເງິນປັນຜົນ</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="font-weight-bold">
                                    <th>
                                        ລ/ດ
                                    </th>
                                    <th>
                                        ຈຳນວນເງິນ
                                    </th>
                                    <th>
                                        ວັນທີ
                                    </th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($withdraws as $key => $withdraw)
                                        <tr>
                                            <td>
                                                {{ $key + 1 }}
                                            </td>
                                            <td>
                                                {{ $withdraw->price }}
                                            </td>
                                            <td>
                                                {{ $withdraw->created_at }}
                                            </td>
                                            <td>
                                                <a href="/withdraw_detail_ch/{{ $withdraw->id }}">
                                                    ລາຍລະອຽດການປັນຜົນ
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
                        <li class="page-item
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
                        <li class="page-item
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
