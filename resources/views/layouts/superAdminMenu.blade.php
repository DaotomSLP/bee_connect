{{-- in-house --}}
<li><a><i class="fa fa-home"></i> ພາຍໃນ <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li class="{{ Request::is('send') || Request::is('send/*') ? 'current-page' : '' }}">
            <a href="/send">ການສົ່ງສິນຄ້າ</a>
        </li>
        <li class="{{ Request::is('home') ? 'current-page' : '' }}"><a href="/home">ລາຍງານປະຈຳວັນ</a></li>
        <li class="{{ Request::is('price') || Request::is('price/*') ? 'current-page' : '' }}">
            <a href="/price">ຕັ້ງຄ່າລາຄາສົ່ງ</a>
        </li>
    </ul>
</li>

{{-- china --}}
<li><a><i class="fa fa-edit"></i> ຕ່າງປະເທດ <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li class="{{ Request::is('import') || Request::is('import/*') ? 'current-page' : '' }}">
            <a href="/import">ນຳເຂົ້າສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('importView') || Request::is('importView/*') || Request::is('serviceChargeDetail*') || Request::is('importDetail*') ? 'current-page' : '' }}">
            <a href="/importView">ລາຍການນຳເຂົ້າສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('importProductTrack') || Request::is('importProductTrack/*') ? 'current-page' : '' }}">
            <a href="/importProductTrack">ຕິດຕາມສິນຄ້າ</a>
        </li>
        <li class="{{ Request::is('dailyImport') ? 'current-page' : '' }}"><a href="/dailyImport">ລາຍງານປະຈຳວັນ</a>
        </li>
        <li class="{{ Request::is('priceImport') || Request::is('priceImport/*') ? 'current-page' : '' }}">
            <a href="/priceImport">ຕັ້ງຄ່າລາຄາ</a>
        </li>
        <li class="{{ Request::is('money_ch') || Request::is('money_ch') ? 'current-page' : '' }}">
            <a href="/money_ch">ຜົນຕອບແທນ</a>
        </li>
        <li class="{{ Request::is('withdraw_ch') || Request::is('withdraw_ch') ? 'current-page' : '' }}">
            <a href="/withdraw_ch">ຖອນເງິນ</a>
        </li>
    </ul>
</li>

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
        <li class="{{ Request::is('money_th') || Request::is('money_th') ? 'current-page' : '' }}">
            <a href="/money_th">ຜົນຕອບແທນ</a>
        </li>
        <li class="{{ Request::is('withdraw_th') || Request::is('withdraw_th') ? 'current-page' : '' }}">
            <a href="/withdraw_th">ຖອນເງິນ</a>
        </li>
    </ul>
</li>

{{-- setting --}}
<li><a><i class="fa fa-desktop"></i> ຕັ້ງຄ່າລະບົບ <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li {{ Request::is('expenditure') || Request::is('expenditure/*') || Request::is('editBranch/*')
            ? 'current-page' : '' }}>
            <a href="/expenditure">ເພີ່ມລາຍຈ່າຍ</a>
        </li>
        <li
            class="{{ Request::is('branchs') || Request::is('branchs/*') || Request::is('editBranch/*') ? 'current-page' : '' }}">
            <a href="/branchs">ສາຂາ</a>
        </li>
        <li
            class="{{ Request::is('users') || Request::is('users/*') || Request::is('editUser/*') ? 'current-page' : '' }}">
            <a href="/users">Users</a>
        </li>

        <li
            class="{{ Request::is('partner') || Request::is('partner/*') || Request::is('editpartner/*') ? 'current-page' : '' }}">
            <a href="/partner">ຫຸ້ນສ່ວນ</a>
        </li>

        @if (Auth::user()->is_owner == 1)
        <li
            class="{{ Request::is('admin') || Request::is('admin/*') || Request::is('editAdmin/*') ? 'current-page' : '' }}">
            <a href="/admin">ແອັດມິນ</a>
        </li>
        @endif
    </ul>
</li>