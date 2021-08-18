@extends('layout')

@section('body')
<!-- End Navbar -->
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>ແກ້ໄຂສາຂາ</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div>
                        <h2 class="card-title">ແກ້ໄຂສາຂາ</h2>
                    </div>
                    <div class="x_content">
                        <form method="POST" action="/updateBranch">
                            <input type="hidden" name="id" value="{{ $branch->id }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ຊື່ສາຂາ</label>
                                        <input type="text" value="{{ $branch->branch_name }}" name="branch_name"
                                            required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ຊື່ເຈົ້າຂອງສາຂາ</label>
                                        <input type="text" value="{{ $branch->first_name }}" name="first_name" required
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ນາມສະກຸນ</label>
                                        <input type="text" value="{{ $branch->last_name }}" name="last_name" required
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ເບີໂທ</label>
                                        <input type="text" value="{{ $branch->phone }}" name="phone" required
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ແຂວງ</label>
                                        <select class="form-control" required id="select_province" required>
                                            <option value="">
                                                ເລືອກ
                                            </option>
                                            @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}" {{ $branch->prov_id == $province->id ?
                                                'selected' : '' }}>
                                                {{ $province->prov_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">ເມືອງ</label>
                                        <select class="form-control" name="district_id" id="select_district" required>
                                            <option value="">
                                                ເລືອກ
                                            </option>
                                            @foreach ($districts as $district)
                                            <option value="{{ $district->id }}" {{ $branch->dist_id == $district->id ?
                                                'selected' : '' }}>
                                                {{ $district->dist_name }}
                                            </option>
                                            @endforeach
                                        </select>
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
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    var district_lists = <?php echo(json_encode($districts)); ?> ;;
    $("#select_province").on("change", function () {
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
    });

</script>

@endsection