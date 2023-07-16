@extends('_layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">User</h1>
        </div> <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">User</li>
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
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title">Daftar User</h3>
            </div> <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="btn-display">Display</label>
                    <button type="button" class="btn btn-primary form-control" id="btn-display"
                      onclick="reloadTableMaster();">Display</button>
                  </div>
                </div> <!-- /.col -->
                <div class="col-sm-2">
                  <div class="form-groupo">
                    <label for="btn-add">Add</label>
                    <a href="{{ route('user.create') }}" class="btn btn-success form-control" id="btn-add">Add</a>
                  </div>
                </div> <!-- /.col -->
              </div> <!-- /.row -->
              <table class="table table-striped table-bordered table-sm w-100" id="table-master">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th class="text-center" style="width: 15%;">Username</th>
                    <th class="text-center" style="width: 20%;">Name</th>
                    <th class="text-center" style="width: 20%;">Email</th>
                    <th class="text-center">Role</th>
                    <th class="text-center" style="width: 10%;">Action</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div> <!-- /.card-body -->
          </div> <!-- /.card -->
        </div> <!-- /.col -->
      </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
  </section> <!-- /.content -->
</div> <!-- /.content-wrapper -->
@endsection
@section('script')
<script>
  let tableMaster;

  $(document).ready(function () {
    initTableMaster();
  });

  /**
   * utility
   */
  function reloadTableMaster() {
    tableMaster.ajax.reload();
  }

  /**
   * init datatable
   */
  function initTableMaster() {
    tableMaster = $('#table-master').DataTable({
      "columnDefs": [{
        render: (data, type, row, meta) => {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
        "searchable": false,
        "orderable": false,
        "targets": 0,
      }, {
        "className": "dt-center",
        "targets": [0, 1, 2, 3, 5]
      }],
      "aLengthMenu": [
        [5, 10, 25, 50, 75, 100, -1],
        [5, 10, 25, 50, 75, 100, "All"]
      ],
      "iDisplayLength": 10,
      processing: true,
      responsive: true,
      serverSide: true,
      destroy: true,
      order: [],
      ajax: "{{ route('datatable.user') }}",
      columns: [
        { data: null, name: null },
        { data: 'username', name: 'username' },
        { data: 'name', name: 'name' },
        { data: 'email', name: 'email' },
        { data: 'roles', name: 'roles' },
        { data: 'action', name: 'action', searchable: false, orderable: false, },
      ],
      rowId: role => `row-${role.id}`
    });
  }
</script>
@endsection