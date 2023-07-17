<aside class="main-sidebar sidebar-dark-maroon elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link">
    <img src="{{ asset('images/logo-asmi.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
      style="opacity: .8">
    <span class="brand-text font-weight-light"><b>ASMI Warehouse</b></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ $USERLOGIN->avatar() }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ $USERLOGIN->name }}</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar nav-compact nav-child-indent flex-column" data-widget="treeview" role="menu"
        data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

        <!-- HOME -->
        <li class="nav-item">
          <a href="{{ route('home') }}" class="nav-link {{ route('home') == request()->url() ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <!-- END HOME -->

        <!-- ADMIN -->
        @if ($USERLOGIN->isAble(['admin-*']))
          <li class="nav-header font-weight-bold">ADMIN</li>
          <li class="nav-item">
            <a href="{{ route('user.index') }}"
              class="nav-link {{ route('user.index') == request()->url() ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>User</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('role.index') }}"
              class="nav-link {{ route('role.index') == request()->url() ? 'active' : '' }}">
              <i class="nav-icon fas fa-id-card"></i>
              <p>Role</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('permission.index') }}"
              class="nav-link {{ route('permission.index') == request()->url() ? 'active' : '' }}">
              <i class="nav-icon fas fa-id-badge"></i>
              <p>Permission</p>
            </a>
          </li>
        @endif
        <!-- END ADMIN -->

        <!-- MASTER -->
        @if($USERLOGIN->isAble(['whs-operator-*', 'whs-tool-*']))
          <li class="nav-header font-weight-bold">MASTER</li>
          @if ($USERLOGIN->isAble(['whs-operator-*']))
            <li class="nav-item">
              <a href="{{ route('operator.index') }}"
                class="nav-link {{ route('operator.index') == request()->url() ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-cog"></i>
                <p>Operator</p>
              </a>
            </li>
          @endif
          @if ($USERLOGIN->isAble(['whs-tool-*']))
            <li class="nav-item">
              <a href="{{ route('tool.index') }}" class="nav-link {{ route('tool.index') == request()->url() ? 'active' : '' }}">
                <i class="nav-icon fas fa-toolbox"></i>
                <p>Tool</p>
              </a>
            </li>
          @endif
        @endif
        <!-- END MASTER -->

        <!-- TRANSAKSI -->
        @if($USERLOGIN->isAble(['whs-pinjtool-*']))
          <li class="nav-header font-weight-bold">TRANSAKSI</li>
            @if ($USERLOGIN->isAble(['whs-pinjtool-*']))
            <li class="nav-item">
              <a href="{{ route('pinjtool.index') }}"
                class="nav-link {{ route('pinjtool.index') == request()->url() ? 'active' : '' }}">
                <i class="nav-icon fas fa-tools"></i>
                <p>Pinjam Tool</p>
              </a>
            </li>
            @endif
          </li>
        @endif
        <!-- END TRANSAKSI -->

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>