@extends('_layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1>Selamat Datang {{ Auth::user()->name }}!</h1>
        </div> <!-- /.col -->
      </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
  </section> <!-- /.content-header -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        @if (isset($no_role))
          <div class="col-sm-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Dashboard</h3>
              </div> <!-- /.card-header -->
              <div class="card-body">
                Akun baru ya? Yuk minta Admin untuk kasih kamu Role!
              </div> <!-- /.card-body -->
            </div>
          </div> <!-- /.card -->
        @endif

        @if (isset($tool_out))
          <div class="col-sm-3">
            <div class="info-box">
              <span class="info-box-icon bg-danger">
                <i class="fas fa-tools"></i>
              </span>
              <div class="info-box-content">
                <span class="info-box-text">Tool Keluar</span>
                <span class="info-box-number">{{ $tool_out }}</span>
              </div>
            </div> <!-- /.info-box -->
          </div> <!-- /.col -->
        @endif

        @if (isset($tool_in))
          <div class="col-sm-3">
            <div class="info-box">
              <span class="info-box-icon bg-success">
                <i class="fas fa-tools"></i>
              </span>
              <div class="info-box-content">
                <span class="info-box-text">Tool Masuk</span>
                <span class="info-box-number">{{ $tool_in }}</span>
              </div>
            </div> <!-- /.info-box -->
          </div>
        @endif

        @if (isset($total_pp))
          <div class="col-sm-3">
            <div class="info-box">
              <span class="info-box-icon bg-warning">
                <i class="fas fa-file-invoice"></i>
              </span>
              <div class="info-box-content">
                <span class="info-box-text">Jumlah PP</span>
                <span class="info-box-number">{{ $total_pp }}</span>
              </div>
            </div> <!-- /.info-box -->
          </div>
        @endif

        @if (isset($total_tool))
          <div class="col-sm-3">
            <div class="info-box">
              <span class="info-box-icon bg-navy">
                <i class="fas fa-toolbox"></i>
              </span>
              <div class="info-box-content">
                <span class="info-box-text">Jumlah Tool</span>
                <span class="info-box-number">{{ $total_tool }}</span>
              </div>
            </div> <!-- /.info-box -->
          </div>
        @endif

      </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
  </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->
@endsection
@section('script')

@endsection