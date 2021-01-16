<div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
    <div class="logo"><a href="/" class="simple-text logo-normal">
            Bee Connect
        </a></div>
    <div class="sidebar-wrapper">
        <ul class="nav">

            <li class="nav-item {{ Request::is('send') || Request::is('send/*') ? 'active' : '' }}">
                <a class="nav-link" href="/send">
                    <i class="material-icons">near_me</i>
                    <p>{{ Auth::user()->is_admin != 1 ? 'ບັນທຶກສິນຄ້າກ່ຽມສົ່ງ' : 'ການສົ່ງສິນຄ້າທັງໝົດພາຍໃນ' }}</p>
                </a>
            </li>
            <li class="nav-item {{ Request::is('home') ? 'active' : '' }}">
                <a class="nav-link" href="/home">
                    <i class="material-icons">dashboard</i>
                    <p>ລາຍງານປະຈຳວັນ (ພາຍໃນ)</p>
                </a>
            </li>

            @if (Auth::user()->is_admin == 1)
                <li class="nav-item {{ Request::is('price') || Request::is('price/*') ? 'active' : '' }}">
                    <a class="nav-link" href="/price">
                        <i class="material-icons">attach_money</i>
                        <p>ຕັ້ງຄ່າລາຄາສົ່ງພາຍໃນ</p>
                    </a>
                </li>
            @endif

            @if (Auth::user()->is_admin != 1)
                <li class="nav-item {{ Request::is('receive') || Request::is('receive/*') ? 'active' : '' }}">
                    <a class="nav-link" href="/receive">
                        <i class="material-icons">transit_enterexit</i>
                        <p>ຮັບສິນຄ້າ (ພາຍໃນ)</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('success') || Request::is('success/*') ? 'active' : '' }}">
                    <a class="nav-link" href="/success">
                        <i class="material-icons">transit_enterexit</i>
                        <p>ສ່ົງສິນຄ້າໃຫ້ລູກຄ້າ (ພາຍໃນ)</p>
                    </a>
                </li>
            @endif

            <hr>

            <li class="nav-item {{ Request::is('import') || Request::is('import/*') ? 'active' : '' }}">
                <a class="nav-link" href="/import">
                    <i class="material-icons">transit_enterexit</i>
                    <p>ນຳເຂົ້າສິນຄ້າຈາກຕ່າງປະເທດ</p>
                </a>
            </li>
            <li
                class="nav-item 
                {{ Request::is('importView') || Request::is('importView/*') || Request::is('importDetail*') || Request::is('importViewForUser') || Request::is('importViewForUser/*') || Request::is('importDetailForUser*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ Auth::user()->is_admin == 1 ? '/importView' : '/importViewForUser' }}">
                    <i class="material-icons">description</i>
                    <p>ລາຍການນຳເຂົ້າຈາກຕ່າງປະເທດ</p>
                </a>
            </li>
            @if (Auth::user()->is_admin != 1)
                <li class="nav-item {{ Request::is('saleImport') || Request::is('saleImport/*') ? 'active' : '' }}">
                    <a class="nav-link" href="/saleImport">
                        <i class="material-icons">transit_enterexit</i>
                        <p>ຂາຍສິນຄ້າ</p>
                    </a>
                </li>
                <li
                    class="nav-item {{ Request::is('saleView') || Request::is('saleView/*') || Request::is('saleDetail') || Request::is('saleDetail/*') ? 'active' : '' }}">
                    <a class="nav-link" href="/saleView">
                        <i class="material-icons">description</i>
                        <p>ປະຫວັດການຂາຍ</p>
                    </a>
                </li>
                <li
                    class="nav-item {{ Request::is('saleImportPrice') || Request::is('saleImportPrice/*') ? 'active' : '' }}">
                    <a class="nav-link" href="/saleImportPrice">
                        <i class="material-icons">attach_money</i>
                        <p>ຕັ້ງຄ່າລາຄາຂາຍ</p>
                    </a>
                </li>
            @endif
            <li
                class="nav-item {{ Request::is('importProductTrack') || Request::is('importProductTrack/*') || Request::is('importProductTrackForUser') || Request::is('importProductTrackForUser/*') ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ Auth::user()->is_admin == 1 ? '/importProductTrack' : '/importProductTrackForUser' }}">
                    <i class="material-icons">search</i>
                    <p>ຕິດຕາມສິນຄ້າ</p>
                </a>
            </li>
            <li class="nav-item {{ Request::is('dailyImport') ? 'active' : '' }}">
                <a class="nav-link" href="/dailyImport">
                    <i class="material-icons">dashboard</i>
                    <p>ລາຍງານປະຈຳວັນ (ຕ່າງປະເທດ)</p>
                </a>
            </li>

            @if (Auth::user()->is_admin == 1)
                <li class="nav-item {{ Request::is('priceImport') || Request::is('priceImport/*') ? 'active' : '' }}">
                    <a class="nav-link" href="/priceImport">
                        <i class="material-icons">attach_money</i>
                        <p>ຕັ້ງຄ່າລາຄາເຄື່ອງນຳເຂົ້າ</p>
                    </a>
                </li>
            @endif

            <hr>

            @if (Auth::user()->is_admin == 1)
                <li
                    class="nav-item {{ Request::is('expenditure') || Request::is('expenditure/*') || Request::is('editBranch/*') ? 'active' : '' }}">
                    <a class="nav-link" href="/expenditure">
                        <i class="material-icons">attach_money</i>
                        <p>ລາຍຈ່າຍ</p>
                    </a>
                </li>
                <li
                    class="nav-item {{ Request::is('branchs') || Request::is('branchs/*') || Request::is('editBranch/*') ? 'active' : '' }}">
                    <a class="nav-link" href="/branchs">
                        <i class="material-icons">storefront</i>
                        <p>ສາຂາ</p>
                    </a>
                </li>
                <li
                    class="nav-item {{ Request::is('users') || Request::is('users/*') || Request::is('editUser/*') ? 'active' : '' }}">
                    <a class="nav-link" href="/users">
                        <i class="material-icons">person</i>
                        <p>User</p>
                    </a>
                </li>
            @endif

        </ul>
    </div>
</div>
