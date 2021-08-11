{{-- Thai --}}
<li><a><i class="fa fa-edit"></i> ຕ່າງປະເທດ (Thai) <span class="fa fa-chevron-down"></span></a>
  <ul class="nav child_menu">
    {{-- <li class="{{ Request::is('addImportTh') || Request::is('addImportTh/*') ? 'current-page' : '' }}">
      <a href="/addImportTh">ຮັບສິນຄ້າ(ສາງໄທ)</a>
    </li> --}}
    <li class="{{ Request::is('importTh') || Request::is('importTh/*') ? 'current-page' : '' }}">
      <a href="/importTh">ນຳເຂົ້າສິນຄ້າ</a>
    </li>
    <li
      class="{{ Request::is('importViewTh') || Request::is('importViewTh/*') || Request::is('importDetailTh*') ? 'current-page' : '' }}">
      <a href="/importViewTh">ລາຍການນຳເຂົ້າສິນຄ້າ</a>
    </li>
    <li
      class="{{ Request::is('importProductTrackTh') || Request::is('importProductTrackTh/*') ? 'current-page' : '' }}">
      <a href="/importProductTrackTh">ຕິດຕາມສິນຄ້າ</a>
    </li>
    <li class="{{ Request::is('dailyImportTh') || Request::is('base_price_th') ? 'current-page' : '' }}">
      <a href="/dailyImportTh">ລາຍງານປະຈຳວັນ</a>
    </li>
  </ul>
</li>