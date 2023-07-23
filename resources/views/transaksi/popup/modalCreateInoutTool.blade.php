<div class="modal fade" id="modalCreateInoutTool" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form Inout Tool</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> <!-- /.modal-header -->
      <div class="modal-body">
        <form id="form-create">

          <div class="form-group row">
            <label for="create_kd_tool" class="col-sm-2 col-form-label">Kode Tool</label>
            <div class="col-sm-4">
              <select name="kd_tool" id="create_kd_tool" class="form-control" onchange="autoFillTool(this);" required>
                <option></option>
                @foreach ($opt_tool as $opt)
                <option value="{{ $opt->kd_tool }}" data-nm_tool="{{ $opt->nm_tool }}"
                  data-jenis_tool="{{ $opt->jenisTool->nm_jenis }}" data-harga="{{ $opt->harga }}">{{ $opt->kd_tool }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="create_nm_tool" class="col-sm-2 col-form-label">Nama Tool</label>
            <div class="col-sm-4">
              <input type="text" name="nm_tool" id="create_nm_tool" class="form-control" placeholder="Nama Tool"
                readonly>
            </div>
          </div>

          <div class="form-group row">
            <label for="create_jenis_tool" class="col-sm-2 col-form-label">Jenis Tool</label>
            <div class="col-sm-4">
              <input type="text" name="jenis_tool" id="create_jenis_tool" class="form-control" placeholder="Jenis Tool"
                readonly>
            </div>
          </div>

          <div class="form-group row">
            <label for="create_harga" class="col-sm-2 col-form-label">Harga</label>
            <div class="col-sm-4">
              <input type="text" name="harga" id="create_harga" class="form-control" placeholder="Harga"
                readonly>
            </div>
          </div>

          <div class="form-group row">
            <label for="create_operator" class="col-sm-2 col-form-label">Operator</label>
            <div class="col-sm-4">
              <select name="operator" id="create_operator" class="form-control" required>
                <option></option>
                @foreach ($opt_operator as $opt)
                <option value="{{ $opt->id }}">{{ $opt->nm_operator . ' # ' . $opt->divisi }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="create_status" class="col-sm-2 col-form-label">Status</label>
            <div class="col-sm-4">
              <div class="icheck-maroon form-check-inline">
                <input type="radio" name="status" id="create_status_masuk" value="MASUK" required>
                <label for="create_status_masuk">Masuk</label>
              </div>
              <div class="icheck-maroon form-check-inline">
                <input type="radio" name="status" id="create_status_keluar" value="KELUAR" required>
                <label for="create_status_keluar">Keluar</label>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label for="create_qty" class="col-sm-2 col-form-label">Qty</label>
            <div class="col-sm-4">
              <input type="number" name="qty" id="create_qty" class="form-control" placeholder="Qty" min="1" step="1" required>
            </div>
          </div>

        </form>
      </div> <!-- /.modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="storeInoutTool();">Simpan</button>
      </div> <!-- /.modal-footer -->
    </div> <!-- /.modal-content -->
  </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->