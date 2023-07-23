@extends('_layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1>Permintaan Pembelian</h1>
        </div> <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Permintaan Pembelian</li>
          </ol>
        </div> <!-- /.col -->
      </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
  </section> <!-- /.content-header -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title">Filter</h3>
            </div> <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="filter_tgl_awal">Tanggal Awal</label>
                    <input type="date" name="filter_tgl_awal" id="filter_tgl_awal" class="form-control"
                      value="{{ \Carbon\Carbon::now()->startOfMonth()->toDateString() }}">
                  </div>
                </div> <!-- /.col -->
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="filter_tgl_akhir">Tanggal Akhir</label>
                    <input type="date" name="filter_tgl_akhir" id="filter_tgl_akhir" class="form-control"
                      value="{{ \Carbon\Carbon::now()->endOfMonth()->toDateString() }}">
                  </div>
                </div> <!-- /.col -->
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="filter_status">Status</label>
                    <select name="filter_status" id="filter_status" class="form-control select2">
                      <option value="ALL">ALL</option>
                      @foreach ($opt_status as $opt)
                      <option value="{{ $opt }}" {{ $opt=='INPUT' ? 'selected' : '' }}>{{ $opt }}</option>
                      @endforeach
                    </select>
                  </div>
                </div> <!-- /.col -->
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="btn-display">Display</label>
                    <button type="button" class="btn btn-primary form-control" id="btn-display"
                      onclick="reloadTableMaster();">Display</button>
                  </div>
                </div> <!-- /.col -->
              </div> <!-- /.row -->
              <table class="table table-bordered table-sm w-100" id="table-master">
                <thead>
                  <tr>
                    <th class="text-center align-middel" style="width: 50px;">No</th>
                    <th class="text-center align-middel" style="width: 10%;">No PP</th>
                    <th class="text-center align-middel" style="width: 10%;">Tanggal</th>
                    <th class="text-center align-middel">Keterangan</th>
                    <th class="text-center align-middel" style="width: 20%;">Approve</th>
                    <th class="text-center align-middel" style="width: 20%;">Purchasing</th>
                    <th class="text-center align-middel" style="width: 10%;">Status</th>
                    <th class="text-center align-middel" style="width: 70px;">Action</th>
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

  function approvePpTool(no_pp) {
    Swal.fire({
      title: 'Approve PP',
      text: 'Anda yakin ingin meng-approve?',
      icon: 'question',
      showConfirmButton: true,
      confirmButtonText: '<i class="fas fa-check"></i> Approve',
      confirmButtonColor: '#007bff',
      showCancelButton: true,
      cancelButtonText: '<i class="fas fa-times"></i> Batal',
      cancelButtonColor: '#dc3545',
      reverseButtons: true,
      focusCancel: true
    }).then(result => {
      if (result.isDismissed) return ;

      let url = "{{ route('pptool.approve', 'param') }}";
      url = url.replace('param', btoa(no_pp));
      
      $('#loading').show();
      $.post(url, { _method: 'patch' }, res => {
        $('#loading').hide();
        Swal.fire(res.title, res.message, res.status);
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
    tableMaster = $('#table-master').on('preXhr.dt', function (e, settings, dt) {
      dt.tgl_awal = $('#filter_tgl_awal').val();
      dt.tgl_akhir = $('#filter_tgl_akhir').val();
      dt.status = $('#filter_status').val();
      dt.page = 'APPROVE';
    }).DataTable({
      "columnDefs": [{
        render: (data, type, row, meta) => {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
        "searchable": false,
        "orderable": false,
        "targets": 0,
      }, {
        "className": "dt-center",
        "targets": [0, 1, 2, 4, 5, 6, 7]
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
      ajax: "{{ route('pptool.dashboard') }}",
      columns: [
        { data: null, name: null },
        { data: 'no_pp', name: 'no_pp' },
        { data: 'tgl_pp', name: 'tgl_pp', render: data => moment(data).format('DD-MM-YYYY') },
        { data: 'keterangan', name: 'keterangan', orderable: false },
        { data: 'tgl_approve', name: 'tgl_approve', render: data => data ? moment(data).format('DD-MM-YYYY HH:mm') : '' },
        { data: 'submit_prch', name: 'submit_prch', render: data => data ? moment(data).format('DD-MM-YYYY HH:mm') : '' },
        { data: 'status_pp', name: 'status_pp', searchable: false, orderable: false, },
        { data: 'action', name: 'action', searchable: false, orderable: false, },
      ]
    });
  }
</script>
@endsection