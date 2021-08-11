{{-- china test --}}
<li><a><i class="fa fa-edit"></i> ຕ່າງປະເທດ (ທົດສອບ) <span
            class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li
            class="{{ Request::is('addChinaProductCh') || Request::is('addChinaProductCh/*') ? 'current-page' : '' }}">
            <a href="/addChinaProductCh">ສັ່ງນຳເຂົ້າສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('importCh') || Request::is('importCh/*') ? 'current-page' : '' }}">
            <a href="/importCh">ນຳເຂົ້າສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('importViewForUserCh') || Request::is('importViewForUserCh/*') || Request::is('importDetailForUser*') ? 'current-page' : '' }}">
            <a href="/importViewForUserCh">ລາຍການນຳເຂົ້າສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('importProductTrackForUserCh') || Request::is('importProductTrackForUserCh/*') ? 'current-page' : '' }}">
            <a href="/importProductTrackForUserCh">ຕິດຕາມສິນຄ້າ</a>
        </li>

        <li
            class="{{ Request::is('saleImportCh') || Request::is('saleImportCh/*') ? 'current-page' : '' }}">
            <a href="/saleImportCh">ຂາຍສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('saleViewCh') || Request::is('saleViewCh/*') || Request::is('saleDetail') || Request::is('saleDetail/*') ? 'current-page' : '' }}">
            <a href="/saleViewCh">ປະຫວັດການຂາຍ</a>
        </li>
        <li
            class="{{ Request::is('saleImportPriceCh') || Request::is('saleImportPriceCh/*') ? 'current-page' : '' }}">
            <a href="/saleImportPriceCh">ຕັ້ງຄ່າລາຄາຂາຍ</a>
        </li>

        <li class="{{ Request::is('dailyImportCh') ? 'current-page' : '' }}"><a
                href="/dailyImportCh">ລາຍງານປະຈຳວັນ</a></li>
    </ul>
</li>