@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>ຕັ້ງຄ່າຖ້ຽວລົດ</h3>
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
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                    </button>
                    <span>
                        <b> Success - </b>ລົບຂໍ້ມູນສຳເລັດ</span>
                </div>
            @endif
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div>
                            <h2>ເພີ່ມຖ້ຽວລົດ</h2>
                        </div>
                        <div class="x_content">
                            <form method="POST" action="/addDeliveryRound">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ຖ້ຽວທີ່</label>
                                            <input type="text" name="round" required class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ເດືອນ</label>
                                            <input type="text" name="month" required class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">ລົດວັນທີ່</label>
                                            <input class="form-control" name="departure_time">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary pull-right px-5">ເພີ່ມ</button>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div>
                            <h2>ຖ້ຽວລົດ</h2>
                        </div>
                        <div class="x_content">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class=" text-primary">
                                        <th>
                                            ລ/ດ
                                        </th>
                                        <th>
                                            ຖ້ຽວທີ່
                                        </th>
                                        <th>
                                            ເດືອນ
                                        </th>
                                        <th>
                                            ລົດວັນທີ່
                                        </th>
                                        <th>

                                        </th>
                                        <th>

                                        </th>
                                    </thead>
                                    <tbody>
                                        @foreach ($delivery_rounds as $key => $delivery_round)
                                            <tr>
                                                <td>
                                                    {{ $pagination['offset'] ? ($pagination['offset'] - 1) * 10 + $key + 1 : $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $delivery_round->round }}
                                                </td>
                                                <td>
                                                    {{ $delivery_round->month }}
                                                </td>
                                                <td>
                                                    {{ $delivery_round->departure_time }}
                                                </td>
                                                <td>
                                                    <a href="/editDeliveryRound/{{ $delivery_round->id }}">
                                                        <i class="material-icons">create</i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="/deleteDeliveryRound/{{ $delivery_round->id }}">
                                                        <i class="material-icons">delete_forever</i>
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
                        <a class="page-link" href="{{ Request::route()->getName() }}?page={{ $pagination['offset'] - 1 }}"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item {{ $pagination['offset'] == '1' ? 'active' : '' }}">
                        <a class="page-link" href="/{{ Request::route()->getName() }}?page=1">1</a>
                    </li>
                    @for ($j = $pagination['offset'] - 25; $j < $pagination['offset'] - 10; $j++)
                        @if ($j % 10 == 0 && $j > 1)
                            <li
                                class="page-item
                        {{ $pagination['offset'] == $j ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else
                        @endif
                    @endfor
                    @for ($i = $pagination['offset'] - 4; $i <= $pagination['offset'] + 4 && $i <= $pagination['offsets']; $i++)
                        @if ($i > 1 && $i <= $pagination['all'])
                            <li class="page-item {{ $pagination['offset'] == $i ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ Request::route()->getName() }}?page={{ $i }}">{{ $i }}</a>
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
                                    href="{{ Request::route()->getName() }}?page={{ $j }}">{{ $j }}</a>
                            </li>
                        @else
                        @endif
                    @endfor
                    <li class="page-item {{ $pagination['offset'] == $pagination['offsets'] ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ Request::route()->getName() }}?page={{ $pagination['offset'] + 1 }}"
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
