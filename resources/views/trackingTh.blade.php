@extends('layouts.app')

@section('content')
<div class="bg-warning">
  <div class="container-custom">
    <div class=" d-flex justify-content-center">
      <a class="font-weight-bold h3 text-dark text-center" href="/tracking">BEE CONNECT</a>
    </div>
  </div>
</div>
<br>
<div class="container-custom">
  <div class="d-flex justify-content-center">
    <div class="row">
      <div class="col">
        <div class="form-group">
          <input type="text" placeholder="ຄົ້ນຫາ" name="search" id="search_input" required
            class="form-control form-control-lg">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <button type="button" onclick="search()" class="btn btn-primary pull-right px-5 btn-lg">ຄົ້ນຫາ</button>
      </div>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col" id="timeline_list">

    </div>
  </div>
</div>
<script>
  function search() {
    var search_input = $("#search_input").val();
    if (search_input.length > 3) {
      $.ajax({
        type: 'POST',
        url: '/searchTrackingTh',
        data: {
          search: search_input,
          '_token': $('meta[name=csrf-token]').attr('content')
        },
        success: function (res) {
          genTimelineList(res);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
          alert('ເກີດຂໍ້ພິດພາດກະລຸນາລອງໃໝ່')
        }
      });
    }
  }

  function genTimelineList(import_products) {

    $("#timeline_list").html(

      import_products.map(val => `<div class="x_panel">
        <div class="">
          <h2>ເລກທີ່ຂົນສົ່ງ : <p>${val.code}</p>
          </h2>
        </div>
        <hr class="m-0">
        <div class="x_content">
          <div class="dashboard-widget-content">
            <ul class="list-unstyled timeline widget">
              <li>
                <div class="block py-4">
                  <div class="block_content">
                    <h2 class="title">
                      <p class="pr-3 d-inline h5 font-weight-bold">ເຊັນຮັບ(ສາງໄທ)</p><a class="d-inline">${val.created_at}</a>
                    </h2>
                  </div>
                </div>
              </li>`
        + (val.receive_bc_at ? `
              <li>
                <div class="block py-3">
                  <div class="block_content">
                    <h2 class="title">
                      <p class="pr-3 d-inline h5 font-weight-bold">ເຊັນຮັບ(ສາງໃຫຍ່ລາວ)</p><a class="d-inline">${val.receive_bc_at}</a>
                    </h2>
                  </div>
                </div>
              </li>
              `: ``)
        + (val.received_at ? `
              <li>
                <div class="block py-3">
                  <div class="block_content">
                    <h2 class="title">
                      <p class="pr-3 d-inline h5 font-weight-bold">ເຊັນຮັບ(ສາຂາ)</p><a class="d-inline">${val.received_at}</a>
                    </h2>
                  </div>
                </div>
              </li>
              `: ``)
        + (val.success_at ? `
              <li>
                <div class="block py-3">
                  <div class="block_content">
                    <h2 class="title">
                      <p class="pr-3 d-inline h5 font-weight-bold">ສຳເລັດ</p><a class="d-inline">${val.success_at}</a>
                    </h2>
                  </div>
                </div>
              </li>`: ``)
            +`</ul>
          </div>
        </div>
      </div>`)

    )
  }
</script>
@endsection