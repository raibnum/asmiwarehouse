@extends('_layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1>Pinjam Tool</h1>
        </div> <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Pinjam Tool</li>
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
                      @foreach ($opt_status as $key => $status)
                      <option value="{{ $key }}">{{ $status }}</option>
                      @endforeach
                    </select>
                  </div>
                </div> <!-- /.col -->
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="btn-display">Display</label>
                    <button type="button" class="btn btn-primary form-control"
                      onclick="reloadTableMaster();">Display</button>
                  </div>
                </div> <!-- /.col -->
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="btn-add">Add</label>
                    <button type="button" class="btn btn-success form-control"
                      onclick="popupModalCreate();">Add</button>
                  </div>
                </div> <!-- /.col -->
              </div> <!-- /.row -->
              <table class="table table-striped table-bordered table-hover table-sm w-100" id="table-master">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th class="text-center" style="width: 15%;">Kode</th>
                    <th class="text-center" style="width: 15%;">Tanggal</th>
                    <th class="text-center">Operator</th>
                    <th class="text-center" style="width: 10%;">Status</th>
                    <th class="text-center" style="width: 50px;">Action</th>
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

<!-- Modal Create -->
@include('transaksi.popup.modalCreatePinjamTool')
@endsection
@section('script')
<script>
  let tableMaster;

  $(document).ready(function () {
    initTableMaster();

    $('#modalCreatePinjamTool').on('show.bs.modal', function () {
      $('#create_operator').select2({
        placeholder: 'Operator',
        width: '100%'
      });

      let tgl = moment().format('YYYY-MM-DD');
      $('#create_tgl').val(tgl);

      $('.select2-tool').select2({
        placeholder: 'Kode Tool',
        width: '100%'
      });
    });
  });

  /**
   * utility
   */
  function reloadTableMaster() {
    tableMaster.ajax.reload();
  }

  /**
   * popup
   */
  function popupModalCreate() {
    $('#modalCreatePinjamTool').modal('show');
  }
  
  /**
   * init datatables
   */
  function initTableMaster() {
    tableMaster = $('#table-master').on('preXhr.dt', function (e, settings, data) {
      data.tgl_awal = $('#filter_tgl_awal').val();
      data.tgl_akhir = $('#filter_tgl_akhir').val();
      data.status = $('#filter_status').val();
    }).DataTable({
      "columnDefs": [
        {
          render: (data, type, row, meta) => meta.row + 1,
          "targets": 0,
        }, {
          "className": "dt-center",
          "targets": [0, 2, 3]
        }
      ],
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
      ajax: "{{ route('pinjtool.dashboard') }}",
      columns: [
        { data: null, name: null, searchable: false },
        { data: 'kd_pinj', name: 'kd_pinj' },
        { data: 'tgl', name: 'tgl' },
        { data: 'operator', name: 'operator' },
        { data: 'status_text', name: 'status_text' },
        { data: 'action', name: 'action', searchable: false, orderable: false, },
      ],
      rowId: operator => `row-${operator.id}`
    })
  }
</script>
@endsection