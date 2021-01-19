<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="/" class="site_title"><span>Bee Connect</span></a>
        </div>

        <div class="clearfix"></div>

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-home"></i> ພາຍໃນ <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li class="{{ Request::is('send') || Request::is('send/*') ? 'current-page' : '' }}"><a
                                    href="/send">ການສົ່ງສິນຄ້າ</a></li>
                            <li class="{{ Request::is('home') ? 'current-page' : '' }}"><a
                                    href="/home">ລາຍງານປະຈຳວັນ</a></li>
                            @if (Auth::user()->is_admin == 1)
                                <li class="{{ Request::is('price') || Request::is('price/*') ? 'current-page' : '' }}">
                                    <a href="/price">ຕັ້ງຄ່າລາຄາສົ່ງ</a>
                                </li>
                            @endif

                            @if (Auth::user()->is_admin != 1)
                                <li
                                    class="{{ Request::is('receive') || Request::is('receive/*') ? 'current-page' : '' }}">
                                    <a href="/receive">ຮັບສິນຄ້າ</a>
                                </li>
                                <li
                                    class="{{ Request::is('success') || Request::is('success/*') ? 'current-page' : '' }}">
                                    <a href="/success">ສ່ົງສິນຄ້າໃຫ້ລູກຄ້າ</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li><a><i class="fa fa-edit"></i> ຕ່າງປະເທດ <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li class="{{ Request::is('import') || Request::is('import/*') ? 'current-page' : '' }}"><a
                                    href="/import">ນຳເຂົ້າສິນຄ້າ</a></li>
                            <li
                                class="{{ Request::is('importView') || Request::is('importView/*') || Request::is('importDetail*') || Request::is('importViewForUser') || Request::is('importViewForUser/*') || Request::is('importDetailForUser*') ? 'current-page' : '' }}">
                                <a
                                    href="{{ Auth::user()->is_admin == 1 ? '/importView' : '/importViewForUser' }}">ລາຍການນຳເຂົ້າສິນຄ້າ</a>
                            </li>
                            <li
                                class="{{ Request::is('importProductTrack') || Request::is('importProductTrack/*') || Request::is('importProductTrackForUser') || Request::is('importProductTrackForUser/*') ? 'current-page' : '' }}">
                                <a
                                    href="{{ Auth::user()->is_admin == 1 ? '/importProductTrack' : '/importProductTrackForUser' }}">ຕິດຕາມສິນຄ້າ</a>
                            </li>

                            @if (Auth::user()->is_admin != 1)
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
                            @endif

                            <li class="{{ Request::is('dailyImport') ? 'current-page' : '' }}"><a
                                    href="/dailyImport">ລາຍງານປະຈຳວັນ</a></li>

                            @if (Auth::user()->is_admin == 1)
                                <li
                                    class="{{ Request::is('priceImport') || Request::is('priceImport/*') ? 'current-page' : '' }}">
                                    <a href="/priceImport">ຕັ້ງຄ່າລາຄາ</a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    @if (Auth::user()->is_admin == 1)
                        <li><a><i class="fa fa-desktop"></i> ຕັ້ງຄ່າລະບົບ <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li
                                    {{ Request::is('expenditure') || Request::is('expenditure/*') || Request::is('editBranch/*') ? 'current-page' : '' }}>
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
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>

        </div>
    </div>
</div>
