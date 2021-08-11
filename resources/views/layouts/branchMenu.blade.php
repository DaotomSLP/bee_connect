{{-- for Branchs --}}
{{-- in-house --}}
<li><a><i class="fa fa-home"></i> ພາຍໃນ <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li class="{{ Request::is('send') || Request::is('send/*') ? 'current-page' : '' }}">
            <a href="/send">ການສົ່ງສິນຄ້າ</a>
        </li>
        <li class="{{ Request::is('home') ? 'current-page' : '' }}"><a
                href="/home">ລາຍງານປະຈຳວັນ</a></li>
        <li
            class="{{ Request::is('allProducts') || Request::is('allProducts/*') ? 'current-page' : '' }}">
            <a href="/allProducts">ການສົ່ງສິນຄ້າພາຍໃນທັງໝົດ</a>
        </li>
        <li
            class="{{ Request::is('receive') || Request::is('receive/*') ? 'current-page' : '' }}">
            <a href="/receive">ຮັບສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('success') || Request::is('success/*') ? 'current-page' : '' }}">
            <a href="/success">ສ່ົງສິນຄ້າໃຫ້ລູກຄ້າ</a>
        </li>
    </ul>
</li>

{{-- china --}}
<li><a><i class="fa fa-edit"></i> ຕ່າງປະເທດ <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        {{-- <li
            class="{{ Request::is('addChinaProduct') || Request::is('addChinaProduct/*') ? 'current-page' : '' }}">
    <a href="/addChinaProduct">ສັ່ງນຳເຂົ້າສິນຄ້າ</a>
</li> --}}
        <li
            class="{{ Request::is('import') || Request::is('import/*') ? 'current-page' : '' }}">
            <a href="/import">ນຳເຂົ້າສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('importViewForUser') || Request::is('importViewForUser/*') || Request::is('importDetailForUser*') ? 'current-page' : '' }}">
            <a href="/importViewForUser">ລາຍການນຳເຂົ້າສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('importProductTrackForUser') || Request::is('importProductTrackForUser/*') ? 'current-page' : '' }}">
            <a href="/importProductTrackForUser">ຕິດຕາມສິນຄ້າ</a>
        </li>

        <li
            class="{{ Request::is('saleImport') || Request::is('saleImport/*') ? 'current-page' : '' }}">
            <a href="/saleImport">ຂາຍສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('saleView') || Request::is('saleView/*') || Request::is('saleDetail') || Request::is('saleDetail/*') ? 'current-page' : '' }}">
            <a href="/saleView">ປະຫວັດການຂາຍ</a>
        </li>
        <li
            class="{{ Request::is('saleImportPrice') || Request::is('saleImportPrice/*') ? 'current-page' : '' }}">
            <a href="/saleImportPrice">ຕັ້ງຄ່າລາຄາຂາຍ</a>
        </li>

        <li class="{{ Request::is('dailyImport') ? 'current-page' : '' }}"><a
                href="/dailyImport">ລາຍງານປະຈຳວັນ</a></li>
    </ul>
</li>

{{-- thai --}}
<li><a><i class="fa fa-edit"></i> ຕ່າງປະເທດ (Thai) <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li
            class="{{ Request::is('importTh') || Request::is('importTh/*') ? 'current-page' : '' }}">
            <a href="/importTh">ນຳເຂົ້າສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('importViewForUserTh') || Request::is('importViewForUserTh/*') || Request::is('importDetailForUser*') ? 'current-page' : '' }}">
            <a href="/importViewForUserTh">ລາຍການນຳເຂົ້າສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('importProductTrackForUserTh') || Request::is('importProductTrackForUserTh/*') ? 'current-page' : '' }}">
            <a href="/importProductTrackForUserTh">ຕິດຕາມສິນຄ້າ</a>
        </li>

        <li
            class="{{ Request::is('saleImportTh') || Request::is('saleImportTh/*') ? 'current-page' : '' }}">
            <a href="/saleImportTh">ຂາຍສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('saleViewTh') || Request::is('saleViewTh/*') || Request::is('saleDetail') || Request::is('saleDetail/*') ? 'current-page' : '' }}">
            <a href="/saleViewTh">ປະຫວັດການຂາຍ</a>
        </li>
        {{-- <li
            class="{{ Request::is('saleImportPriceTh') || Request::is('saleImportPriceTh/*') ? 'current-page' : '' }}">
            <a href="/saleImportPriceTh">ຕັ້ງຄ່າລາຄາຂາຍ</a>
        </li> --}}

        <li class="{{ Request::is('dailyImportTh') ? 'current-page' : '' }}"><a
                href="/dailyImportTh">ລາຍງານປະຈຳວັນ</a></li>
    </ul>
</li>