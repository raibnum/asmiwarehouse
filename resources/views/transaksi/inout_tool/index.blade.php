@extends('_layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1>Inout Tool</h1>
        </div> <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Inout Tool</li>
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
              <h3 class="card-title">Daftar Inout</h3>
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
                      <option value="MASUK">MASUK</option>
                      <option value="KELUAR">KELUAR</option>
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
                    <label for="btn-create">Add</label>
                    <button type="button" class="btn btn-success form-control"
                      onclick="popupModalCreate();">Add</button>
                  </div>
                </div>
              </div> <!-- /.row -->
              <table class="table-bordered table-hover table-sm w-100" id="table-master">
                <thead>
                  <tr>
                    <th class="text-center align-middel" style="width: 30px;">No</th>
                    <th class="text-center align-middel" style="width: 15%;">Tanggal</th>
                    <th class="text-center align-middel" style="width: 15%;">Kode Tool</th>
                    <th class="text-center align-middel">Nama Tool</th>
                    <th class="text-center align-middel">Operator</th>
                    <th class="text-center align-middel" style="width: 10%;">Qty</th>
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

<!-- Modal Create -->
@include('transaksi.popup.modalCreateInoutTool')
<!-- Modal Edit -->
@include('transaksi.popup.modalEditInoutTool')
@endsection
@section('script')
<script>
  let tableMaster;

  $(document).ready(function () {
    $('#modalCreateInoutTool').on('show.bs.modal', function () {
      $('#create_operator').select2({
        dropdownParent: $('#modalCreateInoutTool'),
        placeholder: 'Operator',
        width: '100%'
      });
      
      $('#create_kd_tool').select2({
        dropdownParent: $('#modalCreateInoutTool'),
        placeholder: 'Tool',
        width: '100%'
      });
    });

    $('#modalEditInoutTool').on('show.bs.modal', function () {
      $('#edit_operator').select2({
        dropdownParent: $('#modalEditInoutTool'),
        placeholder: 'Operator',
        width: '100%'
      });
      
      $('#edit_kd_tool').select2({
        dropdownParent: $('#modalEditInoutTool'),
        placeholder: 'Tool',
        width: '100%'
      });
    });

    initTableMaster();
  });

  /**
   * crud
   */
  function deleteInoutTool(id) {
    Swal.fire({
      title: 'Hapus Data',
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
      if (result.isDismissed) return;

      let url = "{{ route('inouttool.destroy', 'param') }}";
      url = url.replace('param', btoa(id));

      $('#loading').show();
      $.post(url, { _method: 'delete' }, res => {
        $('#loading').hide();
        Swal.fire(res.title, res.message, res.status);
        reloadTableMaster();
      }).fail(xhr => {
        $('#loading').hide();
        let res = xhr.responseJSON || {};
        Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
      });
    })
  }

  function storeInoutTool() {
    let requiredLength = $('#form-create [required]').length;
    for (let i = 0; i < requiredLength; i++) {
      let value = $(`#form-create [required]:eq(${i})`).val();
      if (value == '') {
        Swal.fire('Empty', 'Data tidak boleh kosong', 'error');
        return ;
      }
    }

    Swal.fire({
      title: 'Simpan Data',
      text: 'Anda yakin ingin menyimpan?',
      icon: 'question',
      showConfirmButton: true,
      confirmButtonText: '<i class="fas fa-check"></i> Simpan',
      confirmButtonColor: '#007bff',
      showCancelButton: true,
      cancelButtonText: '<i class="fas fa-times"></i> Batal',
      cancelButtonColor: '#dc3545',
      reverseButtons: true,
      focusCancel: true
    }).then(result => {
      if (result.isDismissed) return;

      let url = "{{ route('inouttool.store') }}";
      let data = $('#form-create').serialize();

      $('#loading').show();
      $.post(url, data, res => {
        $('#loading').hide();
        Swal.fire(res.title, res.message, res.status);
        reloadTableMaster();

        $('#modalCreateInoutTool').modal('hide');
        resetFormCreate();
      }).fail(xhr => {
        $('#loading').hide();
        let res = xhr.responseJSON || {};
        Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
      });
    });
  }

  function updateInoutTool(id) {
    let requiredLength = $('#form-edit [required]').length;
    for (let i = 0; i < requiredLength; i++) {
      let value = $(`#form-edit [required]:eq(${i})`).val();
      if (value == '') {
        Swal.fire('Empty', 'Data tidak boleh kosong', 'error');
        return ;
      }
    }

    Swal.fire({
      title: 'Ubah Data',
      text: 'Anda yakin ingin mengubah?',
      icon: 'question',
      showConfirmButton: true,
      confirmButtonText: '<i class="fas fa-check"></i> Ubah',
      confirmButtonColor: '#007bff',
      showCancelButton: true,
      cancelButtonText: '<i class="fas fa-times"></i> Batal',
      cancelButtonColor: '#dc3545',
      reverseButtons: true,
      focusCancel: true
    }).then(result => {
      if (result.isDismissed) return;

      let url = "{{ route('inouttool.update', 'param') }}";
      url = url.replace('param', btoa(id));
      let data = $('#form-edit').serialize();

      $('#loading').show();
      $.post(url, data, res => {
        $('#loading').hide();
        Swal.fire(res.title, res.message, res.status);
        reloadTableMaster();

        $('#modalEditInoutTool').modal('hide');
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
  function autoFillTool(el) {
    let type = $(el).attr('id').split('_')[0];

    let selectedOption = $(el).find('option:selected');
    let nm_tool = selectedOption.data('nm_tool');
    let jenis_tool = selectedOption.data('jenis_tool');
    let harga = selectedOption.data('harga');

    $(`#${type}_nm_tool`).val(nm_tool);
    $(`#${type}_jenis_tool`).val(jenis_tool);
    $(`#${type}_harga`).val(harga);
  }

  function reloadTableMaster() {
    tableMaster.ajax.reload();
  }

  function resetFormCreate() {
    $('#create_kd_tool').val('');
    $('#create_nm_tool').val('');
    $('#create_jenis_tool').val('');
    $('#create_harga').val('');
    $('#create_operator').val('').change();
    $('#modalCreateInoutTool input:checkbox:checked').attr('checked', false);
    $('#create_qty').val();
  }

  /**
   * popup
   */
  function popupModalEdit(el) {
    let row = $(el).closest('tr');
    let data = tableMaster.row(row).data();

    $('#edit_kd_tool').val(data.kd_tool).change();
    $('#edit_operator').val(data.opr.id).change();
    $(`#edit_status_${data.status.toLowerCase()}`).attr('checked', true);
    $('#edit_qty').val(data.qty);
    $('#modalEditInoutTool .modal-footer button:eq(1)').attr('onclick', `updateInoutTool(${data.id});`);

    $('#modalEditInoutTool').modal('show');
  }

  function popupModalCreate() {
    $('#modalCreateInoutTool').modal('show');
  }

  /**
   * init datatable
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
        }, 
        {
          "className": "dt-center",
          "targets": [0, 1, 2, 3, 4, 5, 6, 7]
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
      ajax: "{{ route('inouttool.dashboard') }}",
      columns: [
        { data: null, name: null, searchable: false },
        { data: 'tgl', name: 'tgl', render: data => moment(data).format('DD-MM-YYYY') },
        { data: 'kd_tool', name: 'kd_tool' },
        { data: 'tool.nm_tool', name: 'tool.nm_tool' },
        { data: 'opr.nm_operator', name: 'opr.nm_operator' },
        { data: 'qty', name: 'qty' },
        { data: 'colored_status', name: 'colored_status' },
        { data: 'action', name: 'action', searchable: false, orderable: false, },
      ],
      rowId: pinjtool => `row-${pinjtool.kd_pinj}`
    });
  }
</script>
@endsection