@extends('_layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Permission</h1>
        </div> <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('permission.index') }}">Permission</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </div> <!-- /.col -->
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
              <form action="{{ route('permission.update', base64_encode($permission->id)) }}" method="post">
                @csrf
                @method('put')

                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Permission</label>
                  <div class="col-sm-4">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Permission"
                      maxlength="100" value="{{ $permission->name }}">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="display_name" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-4">
                    <input type="text" name="display_name" id="display_name" class="form-control" placeholder="Name"
                      maxlength="150" value="{{ $permission->display_name }}">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="description" class="col-sm-2 col-form-label">Description</label>
                  <div class="col-sm-4">
                    <input type="text" name="description" id="description" class="form-control"
                      placeholder="Description" maxlength="150" value="{{ $permission->description }}">
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-sm-4 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-default" onclick="history.go(-1);">Kembali</button>
                  </div>
                </div>

              </form>
            </div> <!-- /.card-body -->
          </div> <!-- /.card -->
        </div> <!-- /.col -->
      </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
  </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->
@endsection