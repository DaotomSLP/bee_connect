@extends('layout')

@section('body')
    <!-- End Navbar -->
    <!-- page content -->
    <div class="right_col" role="main">
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
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $user)
                                        <tr>
                                            <td>
                                                {{ $key + 1 }}
                                            </td>
                                            <td>
                                                {{ $user['name'] }} {{ $user['last_name'] }}
                                            </td>
                                            <td>
                                                {{ $user['price'] }}
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
    </div>
    </div>
@endsection
