@extends('_layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Create User</h1>
        </div> <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Create</a></li>
            <li class="breadcrumb-item active">Create</li>
          </ol>
        </div>
      </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
  </section> <!-- /.content-header -->
  <section class="content">
    <div class="container-fluid">
      @include('components.flash')
      <div class="row">
        <div class="col-sm-12">
          <div class="card card-outline card-success">
            <div class="card-header">
              <h3 class="card-title">Form</h3>
            </div> <!-- /.card-header -->
            <div class="card-body">
              <p class="font-weight-bold text-danger">(*) Wajib diisi.</p>
              <form action="{{ route('user.store') }}" method="post">
                @csrf

                <div class="form-group row">
                  <label for="username" class="col-sm-2 col-form-label">Username (*)</label>
                  <div class="col-sm-4">
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username"
                      maxlength="20" onkeypress="return event.key != ' '" required>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Name (*)</label>
                  <div class="col-sm-4">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" maxlength="150" required>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="email" class="col-sm-2 col-form-label">Email (*)</label>
                  <div class="col-sm-4">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email"
                      maxlength="150" required>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="password" class="col-sm-2 col-form-label">Password</label>
                  <div class="col-sm-4">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="roles" class="col-sm-2 col-form-label">Role</label>
                  <div class="col-sm-4">
                    <select name="roles" id="roles" class="form-control" multiple="multiple">
                      @foreach ($roles as $role)
                      <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-sm-4 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-default" onclick="history.go(-1);">Kembali</button>
                  </div>
                </div>

              </form>
            </div> <!-- /.card -->
          </div> <!-- /.card -->
        </div> <!-- /.col -->
      </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
  </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->
@endsection
@section('script')
<script>
  $(document).ready(function () {
    $(function () {
      $('#roles').select2({
        placeholder: 'Role',
        width: '100%'
      });
    });
  });
</script>
@endsection