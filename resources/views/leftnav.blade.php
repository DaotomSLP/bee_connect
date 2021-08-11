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

                    @if (Auth::user()->is_tester == 'yes')

                        @if (Auth::user()->is_admin == 1)
                            @include('layouts.adminMenuForTest')
                        @else
                          @include('layouts.branchMenuForTest')
                        @endif
                    @endif

                    @if (Auth::user()->is_admin != 1 && Auth::user()->branch_id == null)
                        {{-- For Partner --}}
                        <li><a><i class="fa fa-home"></i> ຕ່າງປະເທດ <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li class="{{ Request::is('addImportTh') ? 'current-page' : '' }}"><a
                                        href="/addImportTh">ຮັບສິນຄ້າ</a></li>
                            </ul>
                        </li>
                    @elseif(Auth::user()->is_thai_admin == 1)

                        @include('layouts.thaiAdminMenu')

                    @elseif(Auth::user()->is_thai_admin_in_lao == 1)

                        @include('layouts.thaiAdminInLaoMenu')

                    @elseif(Auth::user()->is_super_admin == 1 )
                        {{-- for Admin --}}

                        @include('layouts.superAdminMenu')

                    @else

                        @include('layouts.branchMenu')

                    @endif
                </ul>
            </div>

        </div>
    </div>
</div>
