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
                    @if (Auth::user()->is_admin != 1 && Auth::user()->branch_id == null)
                        {{-- For Partner --}}
                        <li><a><i class="fa fa-home"></i> ຕ່າງປະເທດ <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li class="{{ Request::is('dailyImport') ? 'current-page' : '' }}"><a
                                        href="/dailyImport">ລາຍງານປະຈຳວັນ</a></li>
                            </ul>
                        </li>
                    @elseif(Auth::user()->is_admin == 1)
                        {{-- for Admin --}}
                        {{-- in-house --}}
                        <li><a><i class="fa fa-home"></i> ພາຍໃນ <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li class="{{ Request::is('send') || Request::is('send/*') ? 'current-page' : '' }}">
                                    <a href="/send">ການສົ່ງສິນຄ້າ</a>
                                </li>
                                <li class="{{ Request::is('home') ? 'current-page' : '' }}"><a
                                        href="/home">ລາຍງານປະຈຳວັນ</a></li>
                                <li
                                    class="{{ Request::is('price') || Request::is('price/*') ? 'current-page' : '' }}">
                                    <a href="/price">ຕັ້ງຄ່າລາຄາສົ່ງ</a>
                                </li>
                            </ul>
                        </li>

                        {{-- china --}}
                        <li><a><i class="fa fa-edit"></i> ຕ່າງປະເທດ <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li
                                    class="{{ Request::is('import') || Request::is('import/*') ? 'current-page' : '' }}">
                                    <a href="/import">ນຳເຂົ້າສິນຄ້າ</a>
                                </li>
                                <li
                                    class="{{ Request::is('importView') || Request::is('importView/*') || Request::is('importDetail*') ? 'current-page' : '' }}">
                                    <a href="/importView">ລາຍການນຳເຂົ້າສິນຄ້າ</a>
                                </li>
                                <li
                                    class="{{ Request::is('importProductTrack') || Request::is('importProductTrack/*') ? 'current-page' : '' }}">
                                    <a href="/importProductTrack">ຕິດຕາມສິນຄ້າ</a>
                                </li>
                                <li class="{{ Request::is('dailyImport') ? 'current-page' : '' }}"><a
                                        href="/dailyImport">ລາຍງານປະຈຳວັນ</a></li>
                                <li
                                    class="{{ Request::is('priceImport') || Request::is('priceImport/*') ? 'current-page' : '' }}">
                                    <a href="/priceImport">ຕັ້ງຄ່າລາຄາ</a>
                                </li>
                            </ul>
                        </li>


                        @if (Auth::user()->is_tester == 'yes')

                            {{-- Thai --}}
                            <li><a><i class="fa fa-edit"></i> ຕ່າງປະເທດ (Thai) <span
                                        class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li
                                        class="{{ Request::is('addImportTh') || Request::is('addImportTh/*') ? 'current-page' : '' }}">
                                        <a href="/addImportTh">ຮັບສິນຄ້າ(ສາງໄທ)</a>
                                    </li>
                                    <li
                                        class="{{ Request::is('importTh') || Request::is('importTh/*') ? 'current-page' : '' }}">
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
                                    <li class="{{ Request::is('dailyImportTh') ? 'current-page' : '' }}"><a
                                            href="/dailyImportTh">ລາຍງານປະຈຳວັນ</a></li>
                                    <li
                                        class="{{ Request::is('priceImportTh') || Request::is('priceImportTh/*') ? 'current-page' : '' }}">
                                        <a href="/priceImportTh">ຕັ້ງຄ່າລາຄາ</a>
                                    </li>
                                </ul>
                            </li>

                        @endif
                        {{-- setting --}}
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
                    @else
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

                        @if (Auth::user()->is_tester == 'yes')
                            {{-- thai --}}
                            <li><a><i class="fa fa-edit"></i> ຕ່າງປະເທດ (Thai) <span
                                        class="fa fa-chevron-down"></span></a>
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
                                    <li
                                        class="{{ Request::is('saleImportPriceTh') || Request::is('saleImportPriceTh/*') ? 'current-page' : '' }}">
                                        <a href="/saleImportPriceTh">ຕັ້ງຄ່າລາຄາຂາຍ</a>
                                    </li>

                                    <li class="{{ Request::is('dailyImportTh') ? 'current-page' : '' }}"><a
                                            href="/dailyImportTh">ລາຍງານປະຈຳວັນ</a></li>
                                </ul>
                            </li>
                        @endif
                    @endif
                </ul>
            </div>

        </div>
    </div>
</div>
