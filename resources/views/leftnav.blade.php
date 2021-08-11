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
                {{-- <h3>General</h3> --}}
                <ul class="nav side-menu">

                    @if (Auth::user()->is_tester == 'yes')

                        @if (Auth::user()->is_admin == 1)
                            @include('layouts.adminMenuForTest')
                        @else
                            @include('layouts.branchMenuForTest')
                        @endif
                    @endif

              
                    @if(Auth::user()->is_thai_admin == 1)

                        @include('layouts.thaiAdminMenu')

                    @elseif(Auth::user()->is_thai_admin_in_lao == 1)

                        @include('layouts.thaiAdminInLaoMenu')

                    @elseif(Auth::user()->is_super_admin == 1 )
                        {{-- for Admin --}}

                        @include('layouts.superAdminMenu')

                    @elseif(Auth::user()->is_ch_partner == 1 )

                        <li
                            class="{{ Request::is('dailyImport')|| Request::is('/') || Request::is('dailyImport') ? 'current-page' : '' }}">
                            <a href="/dailyImport">ລາຍງານປະຈຳວັນ (China)</a>
                        </li>

                    @elseif(Auth::user()->is_thai_partner == 1 )

                        <li
                            class="{{ Request::is('dailyImportTh') || Request::is('dailyImportTh') ? 'current-page' : '' }}">
                            <a href="/dailyImportTh">ລາຍງານປະຈຳວັນ (Thailand)</a>
                        </li>

                    @else

                        @include('layouts.branchMenu')

                    @endif
                </ul>
            </div>

        </div>
    </div>
</div>
