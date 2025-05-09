{{-- Sneat --}}
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  
  <div class="app-brand ">
    <img class="img-fluid app-brand-link mt-3" src="{{asset('assets/img/logo.png')}}" alt="" width="200">

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
    </a>
  </div>

  
  <div class="menu-divider mt-0  "></div>

  <div class="menu-inner-shadow"></div>

  

  <ul class="menu-inner py-1">
    
    <!-- Dashboards -->
    {{-- @hasPermission('VIEW_DASHBOARD') --}}
    <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <a href="{{ route('dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-smile"></i>
        <div class="text-truncate">Beranda</div>
      </a>
    </li>
    <li class="menu-item {{ request()->routeIs('letter.index') || request()->routeIs('letter.detail') ? 'active' : '' }}">
      <a href="{{ route('letter.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-envelope"></i>
        <div class="text-truncate">Surat</div>
      </a>
    </li>
    <li class="menu-item {{ request()->routeIs('letter.history') ? 'active' : '' }}">
      <a href="{{ route('letter.history') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-task"></i>
        <div class="text-truncate">Laporan</div>
      </a>
    </li>
    {{-- <li class="menu-item {{ request()->routeIs('qr.index') ? 'active' : '' }}">
      <a href="{{ route('qr.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-qr"></i>
        <div class="text-truncate">Scan QR</div>
      </a>
    </li> --}}
    {{-- @endhasPermission --}}

    {{-- @hasPermission('VIEW_INVENTARIS')
    <li class="menu-item {{ request()->routeIs('inventaris.index') ? 'active' : '' }}">
      <a href="{{ route('inventaris.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-package"></i>
        <div class="text-truncate">Daftar Barang</div>
      </a>
    </li>
    @endhasPermission

    @hasPermission('VIEW_CEK')
    <li class="menu-item {{ request()->routeIs('cek.index') ? 'active' : '' }}">
      <a href="{{ route('cek.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-clipboard"></i>
        <div class="text-truncate">Pengecekan Barang</div>
      </a>
    </li>
    @endhasPermission

    @hasPermission('VIEW_LOCATION_INVENTARIS')
    <li class="menu-item {{ request()->routeIs('inventaris.location.index') ? 'active' : '' }}">
      <a href="{{ route('inventaris.location.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-current-location"></i>
        <div class="text-truncate">Lokasi Barang</div>
      </a>
    </li>
    @endhasPermission

    @hasPermission('VIEW_BORROW')
    <li class="menu-item {{ request()->routeIs('borrow.index') ? 'active' : '' }}">
      <a href="{{ route('borrow.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-bookmarks"></i>
        <div class="text-truncate">Peminjaman</div>
      </a>
    </li>
    @endhasPermission

    @hasPermission('VIEW_SCAN')
    <li class="menu-item {{ request()->routeIs('qr.scan') ? 'active' : '' }}">
      <a href="{{ route('qr.scan') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-qr-scan"></i>
        <div class="text-truncate">Scan</div>
      </a>
    </li>
    @endhasPermission

    @hasPermission(['VIEW_USERS', 'VIEW_ROLES'])
    <li class="menu-item {{ request()->routeIs('users.index') || request()->routeIs('roles.index') ? 'active' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-cog"></i>
        <div class="text-truncate">Pengaturan</div>
      </a>

      <ul class="menu-sub">
        @hasPermission('VIEW_USERS')
        <li class="menu-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
          <a href="{{ route('users.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div class="text-truncate">Pengguna</div>
          </a>
        </li>
        @endhasPermission
        @hasPermission('VIEW_ROLES')
        <li class="menu-item {{ request()->routeIs('roles.index') ? 'active' : '' }}">
          <a href="{{ route('roles.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user-plus"></i>
            <div class="text-truncate">Jabatan</div>
          </a>
        </li>
        @endhasPermission
      </ul>
    </li>
    @endhasPermission --}}
  </ul>
</aside>