@extends('_layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1>Master Tool</h1>
        </div> <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Master Tool</li>
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
                    <label for="filter_st_aktif">Aktif</label>
                    <select name="filter_st_aktif" id="filter_st_aktif" class="form-control select2">
                      <option value="ALL">ALL</option>
                      <option value="1" selected>AKTIF</option>
                      <option value="0">NON AKTIF</option>
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
                @if (Auth::user()->isAble(['whs-tool-create']))
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
                    <th class="text-center align-middle" style="width: 5%;">No</th>
                    <th class="text-center align-middle" style="width: 15%;">Kode</th>
                    <th class="text-center align-middle">Nama</th>
                    <th class="text-center align-middle" style="width: 10%;">Jenis</th>
                    <th class="text-center align-middle" style="width: 10%;">Stok</th>
                    <th class="text-center align-middle" style="width: 10%;">Stok Minimal</th>
                    <th class="text-center align-middle" style="width: 10%;">Harga</th>
                    <th class="text-center align-middle" style="width: 10%;">Aktif</th>
                    <th class="text-center align-middle" style="width: 10%;">Action</th>
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
@include('master.popup.modalCreateTool')
<!-- Modal Edit -->
@include('master.popup.modalEditTool')

@endsection
@section('script')
<script>
  let tableMaster;

  $(document).ready(function () {
    $('#modalCreateTool').on('show.bs.modal', function () {
      $('#create_kd_jenis').select2({
        dropdownParent: $('#modalCreateTool'),
        placeholder: 'Jenis',
        tags: true,
        width: '100%'
      });
    });

    $('#modalEditTool').on('show.bs.modal', function () {
      $('#edit_kd_jenis').select2({
        dropdownParent: $('#modalEditTool'),
        placeholder: 'Jenis',
        tags: true,
        width: '100%'
      });

      $('#form-edit input[name="_method"]').val('put');
    });

    initTableMaster();
  });

  /**
   * crud
   */
  function storeTool() {
    let length = $('#form-create [required]').length;
    for (let i = 0; i < length; i++) {
      let value = $(`#form-create [required]:eq(${i})`).val();
      if (value == '') {
        Swal.fire('Warning', '(*) Wajib diisi', 'warning');
        return ;
      }
    }

    Swal.fire({
      title: 'Simpan Tool',
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

      let url = "{{ route('tool.store') }}";
      let data = $('#form-create').serialize();

      $('#loading').show();
      $.post(url, data, res => {
        $('#loading').hide();

        $('#modalCreateTool').modal('hide');
        $('#modalCreateTool #form-create :input').val('');
        $('#modalCreateTool #form-create #create_st_aktif_true').prop('checked', true);

        Swal.fire(res.title, res.message, res.status);
        changeOptionJenis(res.data.jenis);
        reloadTableMaster();
      }).fail(xhr => {
        $('#loading').hide();
        let res = xhr.responseJSON || {};
        Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
      });
    })
  }

  function updateTool(kd_tool) {
    let length = $('#form-edit [required]').length;
    for (let i = 0; i < length; i++) {
      let value = $(`#form-edit [required]:eq(${i})`).val();
      if (value == '') {
        Swal.fire('Warning', '(*) Wajib diisi', 'warning');
        return ;
      }
    }

    Swal.fire({
      title: 'Update Tool',
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

      let url = "{{ route('tool.update', 'param') }}";
      url = url.replace('param', btoa(kd_tool));
      let data = $('#form-edit').serialize();

      $('#loading').show();
      $.post(url, data, res => {
        $('#loading').hide();

        $('#modalEditTool').modal('hide');
        $('#modalEditTool #form-edit :input').val('');
        $('#modalEditTool #form-edit #edit_st_aktif_true').prop('checked', true);

        Swal.fire(res.title, res.message, res.status);
        changeOptionJenis(res.data.jenis);
        reloadTableMaster();
      }).fail(xhr => {
        $('#loading').hide();
        let res = xhr.responseJSON || {};
        Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
      });
    });
  }

  function deleteTool(kd_tool) {
    Swal.fire({
      title: 'Hapus Tool',
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

      let url = "{{ route('tool.destroy', 'param') }}";
      url = url.replace('param', btoa(kd_tool));

      $('#loading').show();
      $.post(url, { _method: 'delete' }, res => {
        $('#loading').hide();
        Swal.fire(res.title, res.message, res.status);
        reloadTableMaster();
      }).fail(xhr => {
        let res = xhr.responseJSON || {};
        Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
      });
    });
  }

  /**
   * utility
   */
  function changeOptionJenis(jenis) {
    let option = `
      <option></option>
      ${jenis.map(j => `<option value="${j.kd_jenis}">${j.nm_jenis}</option>`).join('')}
    `;

    $('select[name="kd_jenis"]').html(option);
  }

  function reloadTableMaster() {
    tableMaster.ajax.reload();
  }

  /**
   * popup
   */
  function popupModalCreate() {
    $('#modalCreateTool').modal('show');
  }

  function popupModalEdit(kd_tool) {
    let data = tableMaster.row(`#row-${btoa(kd_tool)}`).data();

    $('#edit_kd_tool').val(data.kd_tool);
    $('#edit_nm_tool').val(data.nm_tool);
    $('#edit_kd_jenis').val(data.kd_jenis).change();
    $('#edit_stok').val(data.stok);
    $('#edit_stok_minimal').val(data.stok_minimal);
    $('#edit_harga').val(data.harga);
    if (data.st_aktif == true) {
      $('#edit_st_aktif_true').prop('checked', true);
    } else {
      $('#edit_st_aktif_false').prop('checked', true);
    }
    if (data.st_sekali_pakai == true) {
      $('#edit_st_sekali_pakai_true').prop('checked', true);
    } else {
      $('#edit_st_sekali_pakai_false').prop('checked', true);
    }

    $('#modalEditTool .modal-footer button:eq(1)').attr('onclick', `updateTool('${kd_tool}');`);

    $('#modalEditTool').modal('show');
  }

  /**
   * init datatable
   */
  function initTableMaster() {
    tableMaster = $('#table-master').on('preXhr.dt', function (e, settings, dt) {
      dt.st_aktif = $('#filter_st_aktif').val();
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
        "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8]
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
      ajax: "{{ route('datatable.tool') }}",
      columns: [
        { data: null, name: null },
        { data: 'kd_tool', name: 'kd_tool' },
        { data: 'nm_tool', name: 'nm_tool' },
        { data: 'nm_jenis', name: 'nm_jenis' },
        { data: 'stok', name: 'stok', searchable: false },
        { data: 'stok_minimal', name: 'stok_minimal', searchable: false },
        { data: 'harga', name: 'harga', searchable: false },
        { data: 'st_aktif_html', name: 'st_aktif_html', orderable: false, searchable: false },
        { data: 'action', name: 'action', searchable: false, orderable: false, },
      ],
      rowId: tool => `row-${btoa(tool.kd_tool)}`
    });
  }
</script>
@endsection