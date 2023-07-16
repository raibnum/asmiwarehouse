@extends('_layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="0">Role</h1>
        </div> <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Role</li>
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
              <h3 class="card-title">Daftar Role</h3>
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
                  <div class="form-group">
                    <label for="btn-add">Add</label>
                    <a href="{{ route('role.create') }}" class="btn btn-success form-control" id="btn-add">Add</a>
                  </div>
                </div> <!-- /.col -->
              </div> <!-- /.row -->
              <table class="table table-striped table-bordered table-sm w-100" id="table-master">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th class="text-center" style="width: 25%;">Role</th>
                    <th class="text-center">Display Name</th>
                    <th class="text-center" style="width: 20%;">Description</th>
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
   * crud
   */
  function deleteRole(id) {
    Swal.fire({
      title: 'Hapus Role',
      text: 'Anda yakin ingin menghapus?',
      icon: 'question',
      showConfirmButton: true,
      confirmButtonText: '<i class="fas fa-check"></i> Hapus',
      confirmButtonColor: '#007bff',
      showCancelButton: true,
      cancelButtonText: '<i class="fas fa-times"></i> Batal',
      cancelButtonColor: '#dc3545',
      reverseButtons: true,
      focusCancel: true
    }).then(result => {
      if (result.isDismissed) return ;

      let url = "{{ route('role.destroy', 'param') }}";
      url = url.replace('param', btoa(id));

      $('#loading').show();
      $.post(url, { _method: 'delete' }, res => {
        $('#loading').hide();
        Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');

        reloadTableMaster();
      }).fail(xhr => {
        $('#loading').hide();
        let res = xhr.responseJSON || {};
        Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
      });
    });
  }

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
        "targets": [0, 4]
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
      ajax: "{{ route('datatable.role') }}",
      columns: [
        { data: null, name: null },
        { data: 'name', name: 'name' },
        { data: 'display_name', name: 'display_name' },
        { data: 'description', name: 'description' },
        { data: 'action', name: 'action', searchable: false, orderable: false, },
      ],
      rowId: role => `row-${role.id}`
    });
  }
</script>
@endsection