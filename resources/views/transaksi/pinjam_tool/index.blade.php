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
              <table class="table table-bordered table-hover table-sm w-100" id="table-master">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th class="text-center" style="width: 15%;">Kode</th>
                    <th class="text-center" style="width: 15%;">Tanggal</th>
                    <th class="text-center">Operator</th>
                    <th class="text-center" style="width: 25%;">Status</th>
                    <th class="text-center" style="width: 100px;">Action</th>
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
<!-- Modal Edit -->
@include('transaksi.popup.modalEditPinjamTool')
@endsection
@section('script')
<script>
  let tableMaster;
  let optTool = {!! json_encode($opt_tool) !!};

  $(document).ready(function () {
    initTableMaster();

    $('#modalCreatePinjamTool').on('show.bs.modal', function () {
      $('#create_operator').select2({
        dropdownParent: $('#modalCreatePinjamTool'),
        placeholder: 'Operator',
        width: '100%'
      });

      let tgl = moment().format('YYYY-MM-DD');
      $('#create_tgl').val(tgl);

      let jam = moment().format('HH:mm');
      $('#create_jam').val(jam);

      $('.select2-tool').select2({
        dropdownParent: $('#modalCreatePinjamTool'),
        placeholder: 'Kode Tool',
        width: '100%'
      });
    });

    $('#modalEditPinjamTool').on('show.bs.modal', function () {
      $('#edit_operator').select2({
        width: '100%'
      });

      $('.select2-tool').select2({
        dropdownParent: $('#modalCreatePinjamTool'),
        width: '100%'
      });
    });
  });

  /**
   * crud
   */
  function deletePinjamTool(kd_pinj) {
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
      if (result.isDismissed) return ;

      let url = "{{ route('pinjtool.destroy', 'param') }}";
      url = url.replace('param', btoa(kd_pinj));

      $('#loading').show();
      $.post(url, { _method: 'delete' }, res => {
        $('#loading').hide();
        Swal.fire(res.title, res.message, res.status);
        reloadTableMaster();

        $('#modalCreatePinjamTool #create_kd_pinj').val(res.data.kd_pinj_baru);
      }).fail(xhr => {
        $('#loading').hide();
        let res = xhr.responseJSON || {};
        Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
      });
    });
  }
  
  function storePinjamTool() {
    let requiredLength = $('#table-create tbody [required]').length;
    for (let i = 0; i < requiredLength; i++) {
      let value = $(`#table-create tbody [required]:eq(${i})`).val();
      if (value == '') {
        Swal.fire('Empty', 'Data tidak boleh kosong', 'error');
        return ;
      }
    }

    let invalidLength = $('#table-create tbody .is-invalid').length;
    if (invalidLength > 0) {
      Swal.fire('Invalid', 'Data invalid', 'error');
      return ;
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
      if (result.isDismissed) return ;

      let url = "{{ route('pinjtool.store') }}";
      let data = $('#form-create').serialize();

      $('#loading').show();
      $.post(url, data, res => {
        $('#loading').hide();
        Swal.fire(res.title, res.message, res.status);
        reloadTableMaster();

        optTool = res.data.opt_tool;
        $('#create_kd_pinj').val(res.data.kd_pinj_baru);
        $('#modalCreatePinjamTool').modal('hide');
        resetFormCreate();
      }).fail(xhr => {
        $('#loading').hide();
        let res = xhr.responseJSON || {};
        Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
      });
    });
  }

  function updatePinjamTool(kd_pinj) {
    Swal.fire({
      title: 'Kembalikan Tool',
      text: 'Anda yakin ingin mengembalikan tool?',
      icon: 'question',
      showConfirmButton: true,
      confirmButtonText: '<i class="fas fa-check"></i> Kembalikan',
      confirmButtonColor: '#007bff',
      showCancelButton: true,
      cancelButtonText: '<i class="fas fa-times"></i> Batal',
      cancelButtonColor: '#dc3545',
      reverseButtons: true,
      focusCancel: true
    }).then(result => {
      if (result.isDismissed) return;

      let url = "{{ route('pinjtool.update', 'param') }}";
      url = url.replace('param', btoa(kd_pinj));
      let data = $('#form-edit').serialize();

      $('#loading').show();
      $.post(url, data, res => {
        $('#loading').hide();
        Swal.fire(res.title, res.message, res.status);
        reloadTableMaster();

        optTool = res.data.opt;
        $('#modalEditPinjamTool').modal('hide');
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
    let selected = $(el).find('option:selected');
    let nm_tool = selected.data('nm_tool');
    let jenis = selected.data('jenis');
    let stok = selected.data('stok');

    let tr = $(el).closest('tr');
    tr.find('td:eq(2) input').val(nm_tool);
    tr.find('td:eq(3) input').val(jenis);
    tr.find('td:eq(4) input').attr('max', stok);
    tr.find('td:eq(4) input').trigger('change');
  }

  function checkDuplicateTool(el) {
    let kd_tool = $(el).val();
    let length = $(el).closest('tbody').find('tr').length;
    let st_duplicate = false;

    $(el).closest('tr').siblings().each(function () {
      let this_kd_tool = $(this).find('td:eq(1) select').val();
      if (this_kd_tool == kd_tool) {
        $(el).addClass('is-invalid');
        st_duplicate = true;
      }
    });

    if (st_duplicate == false) {
      $(el).removeClass('is-invalid');
    }
  }

  function checkMaxValue(el) {
    let max = +($(el).attr('max'));
    let value = +($(el).val());

    if (value > max) {
      $(el).addClass('is-invalid');
    } else {
      $(el).removeClass('is-invalid');
    }
  }

  function reloadTableMaster() {
    tableMaster.ajax.reload();
  }

  function resetFormCreate() {
    $('#create_operator').val('').change();
    $('#table-create tbody').html(rowCreate(1));
  }

  /**
   * row related
   */
  function addRowCreate() {
    let index = $('#table-create tbody tr').length + 1;
    $('#table-create tbody').append(rowCreate(index));

    if (index > 1) $('#table-create tbody tr:eq(0) td:eq(5) button').attr('disabled', false);

    $('.select2-tool').select2({
      dropdownParent: $('#modalCreatePinjamTool'),
      placeholder: 'Kode Tool',
      width: '100%'
    });
  }

  function adjustRowCreate() {
    let trLength = $('#table-create tbody tr').length;
    if (trLength == 1) $('#table-create tbody tr:eq(0) td:eq(5) button').attr('disabled',  true);

    for (let i = 0; i < trLength; i++) {
      let tr = $(`#table-create tbody tr:eq(${i})`);
      tr.attr('id', `row-create-${i + 1}`);
      tr.find('td:eq(0)').html(i + 1);
      tr.find('td:eq(5) button').attr('onclick', `deleteRowCreate(${i + 1});`);
    }
  }

  function deleteRowCreate(index) {
    $(`#row-create-${index}`).remove();
    adjustRowCreate();
  }

  /**
   * html
   */
  function rowCreate(index) {
    let isDeleteDisabled = index == 1 ? 'disabled' : '';
    return `
      <tr id="row-create-${index}">
        <td class="text-center">${index}</td>
        <td>
          <select name="kd_tool[]" class="form-control form-control-sm select2-tool"
            onchange="autoFillTool(this); checkDuplicateTool(this);">
            <option></option>
            ${optTool.map(tool => {
              return `
                <option value="${tool.kd_tool}" data-nm_tool="${tool.nm_tool}" data-jenis="${tool.jenis_tool.nm_jenis}" data-stok="${tool.stok_available}">
                  ${tool.kd_tool}
                </option>
              `;
            }).join('')}
          </select>
          <div class="invalid-feedback">Tool sudah dipilih</div>
        </td>
        <td>
          <input type="text" name="nm_tool[]" class="form-control form-control-sm" readonly>
        </td>
        <td>
          <input type="text" name="jenis_tool[]" class="form-control form-control-sm" readonly>
        </td>
        <td>
          <input type="number" name="qty_tool[]" class="form-control form-control-sm" min="0" step="1"
            onchange="checkMaxValue(this);">
          <div class="invalid-feedback">Stok kurang</div>
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-xs btn-tool text-danger" onclick="deleteRowCreate(${index});" ${isDeleteDisabled}>
            <i class="fa fa-minus"></i>
          </button>
        </td>
      </tr>
    `;
  }

  function rowEdit(index, p2t) {
    return `
      <tr id="row-edit-${index}">
        <td class="text-center">${index}</td>
        <td>
          <input type="text" name="kd_tool[]" class="form-control form-control-sm" value="${p2t.kd_tool || ''}" readonly>
        </td>
        <td>
          <input type="text" name="nm_tool[]" class="form-control form-control-sm" value="${p2t.tool.nm_tool || ''}" readonly>
        </td>
        <td>
          <input type="text" name="jenis_tool[]" class="form-control form-control-sm" value="${p2t.tool.jenis_tool.nm_jenis || ''}" readonly>
        </td>
        <td>
          <input type="number" name="qty_tool[]" class="form-control form-control-sm" value="${p2t.qty || ''}" readonly>
        </td>
        <td class="text-center">
          <div class="icheck-maroon">
            <input type="checkbox" name="st_kembali[]" id="st_kembali-${index}" value="T" ${p2t.tgl_kembali != null ? 'checked' : ''}>
            <label for="st_kembali-${index}"></label>
          </div>
        </td>
      </tr>
    `;
  }

  /**
   * popup
   */
  function popupModalCreate() {
    $('#modalCreatePinjamTool').modal('show');
  }

  function popupModalEdit(el) {
    let row = $(el).closest('tr')
    let data = tableMaster.row(row).data();
    let kd_pinj = data.kd_pinj;
    let tgl = moment(data.tgl);
    let operator = data.opr.id;
    let status = data.status_text;
    let pinjam_tool2s = data.pinjam_tool2s || [];

    $('#edit_kd_pinj').val(kd_pinj);
    $('#edit_tgl').val(tgl.format('YYYY-MM-DD'));
    $('#edit_jam').val(tgl.format('HH:mm'));
    $('#edit_operator').val(operator).change();

    $('#table-edit tbody').html('');
    pinjam_tool2s.forEach((p2t, i) => {
      $('#table-edit tbody').append(rowEdit(i + 1, p2t));
    });

    $('#modalEditPinjamTool .modal-footer button:eq(1)').attr('onclick', `updatePinjamTool('${kd_pinj}');`);

    $('#modalEditPinjamTool').modal('show');
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
        }, 
        {
          "className": "dt-center",
          "targets": [0, 1, 2, 3, 4, 5]
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
        { data: 'tgl', name: 'tgl', render: data => moment(data).format('DD-MM-YYYY HH:mm') },
        { data: 'opr.nm_operator', name: 'opr.nm_operator' },
        { data: 'status', name: 'status', searchable: false, orderable: false },
        { data: 'action', name: 'action', searchable: false, orderable: false, },
      ],
      rowId: pinjtool => `row-${pinjtool.kd_pinj}`
    })
  }
</script>
@endsection