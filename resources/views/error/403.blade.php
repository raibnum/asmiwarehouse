@extends('_layouts.app')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>403 Error Page</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">403 Error Page</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="error-page">
      <h2 class="headline text-danger"> 403</h2>

      <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Maaf! Dilarang masuk.</h3>

        <p>
          Anda tidak memiliki akses ke halaman ini.
          Untuk halaman lain, Anda mungkin ingin kembali ke <a href="{{ route('home') }}">dashboard</a>.
        </p>

        <form class="search-form">
          <form action="{{ route('logout') }}" method="post">
            <button type="submit" class="btn btn-warning">
              <i class="fas fa-sign-out-alt"></i> Logout
            </button>
          </form>
        </form>
      </div>
      <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
  </section>
  <!-- /.content -->
</div>
@endsection