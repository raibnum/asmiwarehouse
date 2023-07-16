<nav class="main-header navbar navbar-expand navbar-maroon navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" data-enable-remember="true" href="#" role="button"><i
          class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <div class="h-100 d-flex align-items-center">
        <p class="m-0 text-dark font-weight-bold" id="current-time"></p>
      </div>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <li class="nav-item">
      <form action="{{ route('logout') }}" method="post" id="form-logout">
        @csrf
        <a class="nav-link" role="button" onclick="document.getElementById('form-logout').submit()">
          <i class="fas fa-sign-out-alt"></i> <span class="font-weight-bold">Logout</span>
        </a>
      </form>
    </li>
  </ul>
</nav>