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
                      <option value="{{ $opt }}">{{ $opt }}</option>
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

                @if (Auth::user()->isAble(['whs-pp-tool-create']))
                <div class="col-sm-2">
                  <div class="form-group">
                    <label for="btn-add">Add</label>
                    <button type="button" class="btn btn-success form-control" id="btn-add"
                      onclick="popupModalCreate();">Add</button>
                  </div>
                </div> <!-- /.col -->
                @endif

              </div> <!-- /.row -->
              <table class="table table-bordered table-sm w-100" id="table-master">
                <thead>
                  <tr>
                    <th class="text-center align-middel" style="width: 50px;">No</th>
                    <th class="text-center align-middel" style="width: 10%;">No PP</th>
                    <th class="text-center align-middel" style="width: 10%;">Tanggal</th>
                    <th class="text-center align-middel">Keterangan</th>
                    <th class="text-center align-middel" style="width: 15%;">Approve</th>
                    <th class="text-center align-middel" style="width: 15%;">Purchasing</th>
                    <th class="text-center align-middel" style="width: 10%;">Status</th>
                    <th class="text-center align-middel" style="width: 100px;">Action</th>
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
@include('transaksi.popup.modalCreatePpTool')
<!-- Modal Edit -->
@include('transaksi.popup.modalEditPpTool')
<!-- Modal Detail -->
@include('transaksi.popup.modalDetailPpTool')
<!-- Modal Invoice -->
@include('transaksi.popup.modalInvoicePpTool')

@endsection
@section('script')
<script>
  let tableMaster;
  let opt_tool = {!! json_encode($opt_tool) !!};

  $(document).ready(function () {
    $('#modalCreatePpTool').on('show.bs.modal', function () {
      $('#form-create .select2-tool').select2({
        dropdownParent: $('#modalCreatePpTool'),
        placeholder: 'Kode Tool',
        width: '100%'
      });

      let tgl = moment().format('YYYY-MM-DD');
      $('#create_tgl_pp').val(tgl);
    });

    $('#modalEditPpTool').on('show.bs.modal', function () {
      $('#form-edit .select2-tool').select2({
        dropdownParent: $('#modalEditPpTool'),
        placeholder: 'Kode Tool',
        width: '100%'
      });
    });
    
    initTableMaster();
  });

  /**
   * crud
   */
  function deletePpTool(no_pp) {
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
      
      let url = "{{ route('pptool.destroy', 'param') }}";
      url = url.replace('param', btoa(no_pp));
      
      $('#loading').show();
      $.post(url, { _method: 'delete' }, res => {
        $('#loading').hide();
        Swal.fire(res.title, res.message, res.status);
        reloadTableMaster();

        $('#create_no_pp').val(res.data.no_pp_baru);
      }).fail(xhr => {
        $('#loading').hide();
        let res = xhr.responseJSON || {};
        Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
      });
    });
  }

  function receivePpTool(no_pp) {
    Swal.fire({
      title: 'Receive PP',
      text: 'Anda yakin ingin menandai receive?',
      icon: 'question',
      showConfirmButton: true,
      confirmButtonText: '<i class="fas fa-check"></i> Receive',
      confirmButtonColor: '#007bff',
      showCancelButton: true,
      cancelButtonText: '<i class="fas fa-times"></i> Batal',
      cancelButtonColor: '#dc3545',
      reverseButtons: true,
      focusCancel: true
    }).then(result => {
      if (result.isDismissed) return ;

      let url = "{{ route('pptool.receive', 'param') }}";
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

  function storePpTool() {
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
      if (result.isDismissed) return;

      let url = "{{ route('pptool.store') }}";
      let data = $('#form-create').serialize();

      $('#loading').show();
      $.post(url, data, res => {
        $('#loading').hide();
        Swal.fire(res.title, res.message, res.status);
        reloadTableMaster();

        $('#create_no_pp').val(res.data.no_pp_baru);
        $('#modalCreatePpTool').modal('hide');
        resetFormCreate();
      }).fail(xhr => {
        $('#loading').hide();
        let res = xhr.responseJSON || {};
        Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
      });
    });
  }

  function updatePpTool(no_pp) {
    let requiredLength = $('#table-edit tbody [required]').length;
    for (let i = 0; i < requiredLength; i++) {
      let value = $(`#table-edit tbody [required]:eq(${i})`).val();
      if (value == '') {
        Swal.fire('Empty', 'Data tidak boleh kosong', 'error');
        return ;
      }
    }

    let invalidLength = $('#table-edit tbody .is-invalid').length;
    if (invalidLength > 0) {
      Swal.fire('Invalid', 'Data invalid', 'error');
      return ;
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

      let url = "{{ route('pptool.update', 'param') }}";
      url = url.replace('param', btoa(no_pp));
      let data = $('#form-edit').serialize();

      $('#loading').show();
      $.post(url, data, res => {
        $('#loading').hide();
        Swal.fire(res.title, res.message, res.status);
        reloadTableMaster();

        $('#modalEditPpTool').modal('hide');
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

    let tr = $(el).closest('tr');
    tr.find('td:eq(2) input').val(nm_tool);
    tr.find('td:eq(3) input').val(jenis);
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

  function reloadTableMaster() {
    tableMaster.ajax.reload();
  }

  function resetFormCreate() {
    $('#create_keterangan').val('');
    $('#table-create tbody').html(rowCreate(1));
  }

  /**
   * row related
   */
  function addRowCreate() {
    let index = $('#table-create tbody tr').length + 1;
    $('#table-create tbody').append(rowCreate(index));

    if (index > 1) $('#table-create tbody tr:eq(0) td:eq(5) button').prop('disabled', false);

    $('.select2-tool').select2({
      dropdownParent: $('#modalCreatePpTool'),
      placeholder: 'Kode Tool',
      width: '100%'
    });
  }

  function addRowEdit() {
    let index = $('#table-edit tbody tr').length + 1;
    $('#table-edit tbody').append(rowEdit(index));

    if (index > 1) $('#table-edit tbody tr:eq(0) td:eq(5) button').prop('disabled', false);

    $('#form-edit .select2-tool').select2({
      dropdownParent: $('#modalEditPpTool'),
      placeholder: 'Kode Tool',
      width: '100%'
    });
  }

  function adjustRowCreate() {
    let trLength = $('#table-create tbody tr').length;
    if (trLength == 1) $('#table-create tbody tr:eq(0) td:eq(5) button').prop('disabled',  true);

    for (let i = 0; i < trLength; i++) {
      let tr = $(`#table-create tbody tr:eq(${i})`);
      tr.attr('id', `row-create-${i + 1}`);
      tr.find('td:eq(0)').html(i + 1);
      tr.find('td:eq(5) button').attr('onclick', `deleteRowCreate(${i + 1});`);
    }
  }

  function adjustRowEdit() {
    let trLength = $('#table-edit tbody tr').length;
    if (trLength == 1) $('#table-edit tbody tr:eq(0) td:eq(5) button').prop('disabled',  true);

    for (let i = 0; i < trLength; i++) {
      let tr = $(`#table-edit tbody tr:eq(${i})`);
      tr.attr('id', `row-edit-${i + 1}`);
      tr.find('td:eq(0)').html(i + 1);

      let wasOnclick = tr.find('td:eq(5) button').attr('onclick').split(',')[1].trim();
      tr.find('td:eq(5) button').attr('onclick', `deleteRowEdit(${i + 1}, ${wasOnclick}`);
    }
  }

  function deleteRowCreate(index) {
    $(`#row-create-${index}`).remove();
    adjustRowCreate();
  }

  async function deleteRowEdit(index, type = 'remove') {
    if (type == 'destroy') {
      await new Promise((resolve, reject) => {
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
          if (result.isDismissed) return;
          
          let tr = $(`#row-edit-${index}`);
          let kd_tool = tr.find('td:eq(1) input').val();
          let no_pp = $('#edit_no_pp').val();
  
          let url = "{{ route('pptool.destroyItem', ['param', 'param1']) }}";
          url = url.replace('param2', btoa(kd_tool));
          url = url.replace('param', btoa(no_pp));
  
          $('#loading').show();
          $.post(url,{ _method: 'delete' }, res => {
            $('#loading').hide();
            Swal.fire(res.title, res.message, res.status);
            reloadTableMaster();
            resolve(null);
          }).fail(xhr => {
            $('#loading').hide();
            let res = xhr.responseJSON || {};
            Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
            reject(null);
          });
        });
      });
    }

    $(`#row-edit-${index}`).remove();
    adjustRowEdit();
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
          <select name="kd_tool[]" class="form-control form-control-sm select2-tool" onchange="autoFillTool(this); checkDuplicateTool(this);" required>
            <option></option>
            ${opt_tool.map(opt => {
              return `
                <option value="${opt.kd_tool}" data-nm_tool="${opt.nm_tool}" data-jenis="${opt.jenis_tool.nm_jenis}">${opt.kd_tool}</option>
              `;
            }).join('')}
          </select>
          <div class="invalid-feedback">Tool sudah dipilih</div>
        </td>
        <td>
          <input type="text" name="nm_tool[]" class="form-control form-control-sm" placeholder="Nama Tool" readonly>
        </td>
        <td>
          <input type="text" name="jenis_tool[]" class="form-control form-control-sm" placeholder="Jenis Tool" readonly>
        </td>
        <td>
          <input type="number" name="qty[]" class="form-control form-control-sm" required>
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-xs btn-tool text-danger" onclick="deleteRowCreate(${index});" ${isDeleteDisabled}>
            <i class="fas fa-minus"></i>
          </button>
        </td>
      </tr>
    `;
  }

  function rowDetail(index, ppt2 = null) {
    let kd_tool = '';
    let qty = '';

    if (ppt2 != null) {
      kd_tool = ppt2.kd_tool || '';
      qty = ppt2.qty || '';
    }
    return `
      <tr id="row-edit-${index}">
        <td class="text-center">${index}</td>
        <td>
          <select name="kd_tool[]" class="form-control form-control-sm select2-tool" onchange="autoFillTool(this);" disabled>
            <option></option>
            ${opt_tool.map(opt => {
              return `
                <option value="${opt.kd_tool}" data-nm_tool="${opt.nm_tool}" data-jenis="${opt.jenis_tool.nm_jenis}" ${kd_tool == opt.kd_tool ? 'selected' : ''}>${opt.kd_tool}</option>
              `;
            }).join('')}
          </select>
        </td>
        <td>
          <input type="text" name="nm_tool[]" class="form-control form-control-sm" placeholder="Nama Tool" readonly>
        </td>
        <td>
          <input type="text" name="jenis_tool[]" class="form-control form-control-sm" placeholder="Jenis Tool" readonly>
        </td>
        <td>
          <input type="number" name="qty[]" class="form-control form-control-sm" value="${qty}" readonly>
        </td>
      </tr>
    `;
  }

  function rowEdit(index, ppt2 = null) {
    let kd_tool = '';
    let qty = '';
    let st_delete = 'remove';

    if (ppt2 != null) {
      kd_tool = ppt2.kd_tool || '';
      qty = ppt2.qty || '';
      st_delete = 'destroy';
    }

    let isDeleteDisabled = index == 1 ? 'disabled' : '';
    return `
      <tr id="row-edit-${index}">
        <td class="text-center">${index}</td>
        <td>
          <select name="kd_tool[]" class="form-control form-control-sm select2-tool" onchange="autoFillTool(this); checkDuplicateTool(this);" required>
            <option></option>
            ${opt_tool.map(opt => {
              return `
                <option value="${opt.kd_tool}" data-nm_tool="${opt.nm_tool}" data-jenis="${opt.jenis_tool.nm_jenis}" ${kd_tool == opt.kd_tool ? 'selected' : ''}>${opt.kd_tool}</option>
              `;
            }).join('')}
          </select>
          <div class="invalid-feedback">Tool sudah dipilih</div>
        </td>
        <td>
          <input type="text" name="nm_tool[]" class="form-control form-control-sm" placeholder="Nama Tool" readonly>
        </td>
        <td>
          <input type="text" name="jenis_tool[]" class="form-control form-control-sm" placeholder="Jenis Tool" readonly>
        </td>
        <td>
          <input type="number" name="qty[]" class="form-control form-control-sm" value="${qty}" required>
        </td>
        <td class="text-center">
          <button type="button" class="btn btn-xs btn-tool text-danger" onclick="deleteRowEdit(${index}, '${st_delete}');" ${isDeleteDisabled}>
            <i class="fas fa-minus"></i>
          </button>
        </td>
      </tr>
    `;
  }

  /**
   * popup
   */
  function popupModalCreate() {
    $('#modalCreatePpTool').modal('show');
  }

  function popupModalDetail(el) {
    let row = $(el).closest('tr');
    let data = tableMaster.row(row).data();

    let no_pp = data.no_pp || '';
    let tgl_pp = moment(data.tgl_pp).format('YYYY-MM-DD');
    let keterangan = data.keterangan || '';
    let ppt2s = data.pp_tool2s || [];

    $('#detail_no_pp').val(no_pp);
    $('#detail_tgl_pp').val(tgl_pp);
    $('#detail_keterangan').val(keterangan);

    $('#table-detail tbody').html('');
    ppt2s.forEach((ppt2, i) => {
      $('#table-detail tbody').append(rowDetail(i + 1, ppt2));
    });

    $('#table-detail tbody .select2-tool').trigger('change');

    $('#modalDetailPpTool').modal('show');
  }

  function popupModalEdit(el) {
    let row = $(el).closest('tr');
    let data = tableMaster.row(row).data();

    let no_pp = data.no_pp || '';
    let tgl_pp = moment(data.tgl_pp).format('YYYY-MM-DD');
    let keterangan = data.keterangan || '';
    let ppt2s = data.pp_tool2s || [];
    
    $('#edit_no_pp').val(no_pp);
    $('#edit_tgl_pp').val(tgl_pp);
    $('#edit_keterangan').val(keterangan);

    $('#table-edit tbody').html('');
    ppt2s.forEach((ppt2, i) => {
      $('#table-edit tbody').append(rowEdit(i + 1, ppt2));
    });

    $('#table-edit tbody .select2-tool').trigger('change');

    $('#modalEditPpTool .modal-footer button:eq(1)').attr('onclick', `updatePpTool('${no_pp}');`)

    $('#modalEditPpTool').modal('show');
  }

  function popupModalInvoice(no_pp) {
    let url = "{{ route('pptool.getInvoice', 'param') }}";
    url = url.replace('param', btoa(no_pp));

    $('#loading').show();
    $.get(url, res => {
      $('#loading').hide();

      let invoice = res.data.invoice;

      $('#modalInvoicePpTool #invoice-container').html(invoice);
      $('#modalInvoicePpTool').modal('show');
    }).fail(xhr => {
      $('#loading').hide();
      let res = xhr.responseJSON || {};
      Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
    });
  }

  /**
   * init datatable
   */
  function initTableMaster() {
    tableMaster = $('#table-master').on('preXhr.dt', function (e, settings, dt) {
      dt.tgl_awal = $('#filter_tgl_awal').val();
      dt.tgl_akhir = $('#filter_tgl_akhir').val();
      dt.status = $('#filter_status').val();
      dt.page = 'INPUT';
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