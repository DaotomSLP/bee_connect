{{-- china test --}}
<li><a><i class="fa fa-edit"></i> ຕ່າງປະເທດ (ທົດສອບ) <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li class="{{ Request::is('importCh') || Request::is('importCh/*') ? 'current-page' : '' }}">
            <a href="/importCh">ນຳເຂົ້າສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('importViewCh') || Request::is('importViewCh/*') || Request::is('importDetail*') ? 'current-page' : '' }}">
            <a href="/importViewCh">ລາຍການນຳເຂົ້າສິນຄ້າ</a>
        </li>
        <li
            class="{{ Request::is('importProductTrackCh') || Request::is('importProductTrackCh/*') ? 'current-page' : '' }}">
            <a href="/importProductTrackCh">ຕິດຕາມສິນຄ້າ</a>
        </li>
        <li class="{{ Request::is('dailyImportCh') ? 'current-page' : '' }}"><a href="/dailyImportCh">ລາຍງານປະຈຳວັນ</a>
        </li>
        <li class="{{ Request::is('priceImportCh') || Request::is('priceImportCh/*') ? 'current-page' : '' }}">
            <a href="/priceImportCh">ຕັ້ງຄ່າລາຄາ</a>
        </li>
    </ul>
</li>