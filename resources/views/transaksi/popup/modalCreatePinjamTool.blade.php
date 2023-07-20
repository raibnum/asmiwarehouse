<div class="modal fade" id="modalCreatePinjamTool" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form Pinjam Tool</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> <!-- /.modal-header -->
      <div class="modal-body">
        <form id="form-create">

          <div class="form-group row">
            <label for="create_kd_pinj" class="col-sm-2 col-form-label">Kode</label>
            <div class="col-sm-4">
              <input type="text" name="kd_pinj" id="create_kd_pinj" class="form-control" placeholder="Kode Pinjam"
                value="{{ $kd_pinj }}" readonly>
            </div>
          </div>

          <div class="form-group row">
            <label for="create_tgl" class="col-sm-2 col-form-label">Tanggal</label>
            <div class="col-sm-4">
              <input type="date" name="tgl" id="create_tgl" class="form-control">
            </div>
          </div>

          <div class="form-group row">
            <label for="create_operator" class="col-sm-2 col-form-label">Operator</label>
            <div class="col-sm-4">
              <select name="operator" id="create_operator" class="form-control">
                <option></option>
                @foreach ($opt_operator as $operator)
                <option value="{{ $operator->id }}">{{ $operator->nm_operator . ' # ' . $operator->divisi }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <table class="table table-striped table-bordered table-hover table-sm w-100" id="table-master">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">No</th>
                <th class="text-center" style="width: 20%;">Tool</th>
                <th class="text-center">Nama</th>
                <th class="text-center" style="width: 20%;">Jenis</th>
                <th class="text-center" style="width: 15%;">Qty</th>
                <th class="text-center" style="width: 50px;">
                  <button type="button" class="btn btn-xs btn-default text-success">
                    <i class="fa fa-plus"></i>
                  </button>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr id="row-create-1">
                <td class="text-center">1</td>
                <td>
                  <select name="kd_tool[]" class="form-control form-control-sm select2-tool" onchange="autoFillTool(this);">
                    @foreach ($opt_tool as $tool)
                    <option value="{{ $tool->kd_tool }}" data-nm_tool="{{ $tool->nm_tool }}"
                      data-jenis="{{ $tool->jenis_tool }}" data-stok="{{ $tool->stok }}">{{ $tool->kd_tool }}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <input type="text" name="nm_tool[]" class="form-control form-control-sm" readonly>
                </td>
                <td>
                  <input type="text" name="jenis_tool[]" class="form-control form-control-sm" readonly>
                </td>
                <td>
                  <input type="number" name="qty_tool[]" class="form-control form-control-sm" min="0" step="">
                </td>
              </tr>
            </tbody>
          </table>

        </form>
      </div> <!-- /.modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="storePinjamTool();">Simpan</button>
      </div> <!-- /.modal-footer -->
    </div> <!-- /.modal-content -->
  </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->