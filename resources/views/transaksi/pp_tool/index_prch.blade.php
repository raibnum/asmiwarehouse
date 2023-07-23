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
                      <option value="{{ $opt }}" {{ $opt=='APPROVE' ? 'selected' : '' }}>{{ $opt }}</option>
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
    initTableMaster();
  });

  function prchPpTool(no_pp) {
    Swal.fire({
      title: 'Tandai PP',
      text: 'Anda yakin ingin menandai PP sudah dibeli?',
      html: `
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <button class="btn btn-primary" type="button" id="btn-invoice" onclick="document.getElementById('invoice').click();">
              <i class="fas fa-file-upload"></i>
            </button>
            <input type="file" name="invoice" id="invoice" class="d-none" onchange="document.getElementById('invoice-text').value = this.files[0].name || ''">
          </div>
          <input type="text" class="form-control" id="invoice-text" placeholder="Upload invoice (optional)" disabled>
        </div>
      `,
      preConfirm: () => document.getElementById('invoice').files[0],
      icon: 'question',
      showConfirmButton: true,
      confirmButtonText: '<i class="fas fa-check"></i> Tandai',
      confirmButtonColor: '#007bff',
      showCancelButton: true,
      cancelButtonText: '<i class="fas fa-times"></i> Batal',
      cancelButtonColor: '#dc3545',
      reverseButtons: true,
      focusCancel: true
    }).then(result => {
      if (result.isDismissed) return;

      let url = "{{ route('pptool.prch', 'param') }}";
      url = url.replace('param', btoa(no_pp));

      let formData = new FormData();
      formData.append('invoice', result.value);

      $('#loading').show();
      $.ajax({
        url: url,
        type: 'post',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: res => {
          $('#loading').hide();
          Swal.fire(res.title, res.message, res.status);
          reloadTableMaster();
        },
        error: xhr => {
          $('#loading').hide();
          let res = xhr.responseJSON || {};
          Swal.fire(res.title || 'Failed', res.message || 'Terjadi kesalahan pada system, harap coba lagi', res.status || 'error');
        }
      });
    });
  }

  /**
   * popup
   */
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
      dt.page = 'PRCH';
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