@extends('_layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1>Master Operator</h1>
        </div> <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Master Operator</li>
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
              <h3 class="card-title">Daftar Operator</h3>
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
                @if (Auth::user()->isAble(['whs-operator-create']))
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="btn-add">Add</label>
                    <button type="button" class="btn btn-success form-control" id="btn-add"
                      onclick="popupModalCreate();">Add</button>
                  </div>
                </div> <!-- /.col -->
                @endif
              </div> <!-- /.row -->
              <table class="table tale-striped table-bordered table-sm w-100" id="table-master">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center" style="width: 30%;">Divisi</th>
                    <th class="text-center" style="width: 15%;">Action</th>
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
@include('master.popup.modalCreateOperator')
<!-- Modal Edit -->
@include('master.popup.modalEditOperator')

@endsection
@section('script')
<script>
  let tableMaster;

  $(document).ready(function () {
    $('#modalCreateOperator').on('show.bs.modal', function () {
      $('#create_divisi').select2({
        dropdownParent: $('#modalCreateOperator'),
        placeholder: 'Pilih Divisi',
        tags: true,
        width: '100%'
      });
    });

    $('#modalEditOperator').on('show.bs.modal', function () {
      $('#edit_divisi').select2({
        dropdownParent: $('#modalEditOperator'),
        tags: true,
        width: '100%'
      });
    });

    initTableMaster();
  });

  /**
   * crud
   */
  function storeOperator() {
    Swal.fire({
      title: 'Simpan Operator',
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
      if (result.isDismissed) return ;

      let url = "{{ route('operator.store') }}";
      let data = $('#form-create').serialize();

      $('#loading').show();
      $.post(url, data, res => {
        $('#loading').hide();

        $('#modalCreateOperator').modal('hide');
        $('#modalCreateOperator #create_nm_operator').val('');
        $('#modalCreateOperator #create_divisi').val('').change();

        Swal.fire(res.title, res.message, res.status);
        changeOptionDivisi(res.data.divisi);
        reloadTableMaster();
      }).fail(xhr => {
        $('#loading').hide();
        let res = xhr.responseJSON || {};
        Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
      });
    });
  }

  function updateOperator(id) {
    Swal.fire({
      title: 'Edit Operator',
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
      if (result.isDismissed) return ;

      let url = "{{ route('operator.update', 'param') }}";
      url = url.replace('param', btoa(id));
      let data = $('#form-edit').serialize();

      $('#loading').show();
      $.post(url, data, res => {
        $('#loading').hide();

        $('#modalEditOperator').modal('hide');
        $('#modalEditOperator #edit_nm_operator').val('');
        $('#modalEditOperator #edit_divisi').val('').change();

        $('#modalEditOperator .modal-footer button:eq(1)').attr('onclick', `updateOperator()`);

        Swal.fire(res.title, res.message, res.status);
        changeOptionDivisi(res.data.divisi);
        reloadTableMaster();
      }).fail(xhr => {
        $('#loading').hide();
        let res = xhr.responseJSON || {};
        Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
      });
    });
  }

  function deleteOperator(id) {
    Swal.fire({
      title: 'Hapus Operator',
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

      let url = "{{ route('operator.destroy', 'param') }}";
      url = url.replace('param', btoa(id));

      $('#loading').show();
      $.post(url, { _method: 'delete' }, res => {
        $('#loading').hide();

        Swal.fire(res.title, res.message, res.status);
        changeOptionDivisi(res.data.divisi);
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
  function changeOptionDivisi(divisi) {
    $('#create_divisi').html(optionDivisi(divisi));
    $('#edit_divisi').html(optionDivisi(divisi));
  }

  function reloadTableMaster() {
    tableMaster.ajax.reload();
  }

  /**
   * html
   */
  function optionDivisi(divisi) {
    return `
      <option></option>
      ${divisi.map(d => `<option value="${d}">${d}</option>`).join('')}
    `;
  }

  /**
   * popup modal
   */
  function popupModalCreate() {
    $('#modalCreateOperator').modal('show');
  }

  function popupModalEdit(id) {
    let row = `#row-${id}`;
    let data = tableMaster.row(row).data();

    $('#modalEditOperator #edit_nm_operator').val(data.nm_operator);
    $('#modalEditOperator #edit_divisi').val(data.divisi).change();

    $('#modalEditOperator .modal-footer button:eq(1)').attr('onclick', `updateOperator(${id})`);

    $('#modalEditOperator').modal('show');
  }

  /**
   * init datatable
   */
  function initTableMaster() {
    tableMaster = $('#table-master').DataTable({
      "columnDefs": [{
        render: (data, type, row, meta) => meta.row + 1,
        "searchable": false,
        "orderable": false,
        "targets": 0,
      }, {
        "className": "dt-center",
        "targets": [0, 2, 3]
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
      ajax: "{{ route('datatable.operator') }}",
      columns: [
        { data: null, name: null },
        { data: 'nm_operator', name: 'nm_operator' },
        { data: 'divisi', name: 'divisi' },
        { data: 'action', name: 'action', searchable: false, orderable: false, },
      ],
      rowId: operator => `row-${operator.id}`
    });
  }
</script>
@endsection