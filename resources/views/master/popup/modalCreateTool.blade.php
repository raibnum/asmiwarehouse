<div class="modal fade" id="modalCreateTool" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Tool</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> <!-- /.modal-header -->
      <div class="modal-body">
        <p class="font-weight-bold text-danger">(*) Wajib diisi</p>
        <form id="form-create">

          <div class="form-group row">
            <label for="create_kd_tool" class="col-sm-2 col-form-label">Kode (*)</label>
            <div class="col-sm-4">
              <input type="text" name="kd_tool" id="create_kd_tool" class="form-control" placeholder="Kode"
                maxlength="50" required>
            </div>
          </div>

          <div class="form-group row">
            <label for="create_nm_tool" class="col-sm-2 col-form-label">Nama (*)</label>
            <div class="col-sm-4">
              <input type="text" name="nm_tool" id="create_nm_tool" class="form-control" placeholder="Nama"
                maxlength="150" required>
            </div>
          </div>

          <div class="form-group row">
            <label for="create_kd_jenis" class="col-sm-2 col-form-label">Jenis (*)</label>
            <div class="col-sm-4">
              <select name="kd_jenis" id="create_kd_jenis" class="form-control" required>
                <option></option>
                @foreach ($jenis_tool as $jenis)
                <option value="{{ $jenis->kd_jenis }}">{{ $jenis->nm_jenis }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="create_stok" class="col-sm-2 col-form-label">Stok Awal</label>
            <div class="col-sm-4">
              <input type="number" name="stok" id="create_stok" class="form-control" placeholder="0" min="0" step="1">
            </div>
          </div>

          <div class="form-group row">
            <label for="create_stok_minimal" class="col-sm-2 col-form-label">Stok Minimal</label>
            <div class="col-sm-4">
              <input type="number" name="stok_minimal" id="create_stok_minimal" class="form-control" placeholder="0"
                min="0" step="1">
            </div>
          </div>

          <div class="form-group row">
            <label for="create_harga" class="col-sm-2 col-form-label">Harga</label>
            <div class="col-sm-4">
              <input type="number" name="harga" id="create_harga" class="form-control" placeholder="0" min="0"
                step="1000">
            </div>
          </div>

          <div class="form-group row">
            <label for="create_st_aktif" class="col-sm-2 col-form-label">Status Aktif</label>
            <div class="col-sm-4">
              <div class="form-check form-check-inline icheck-maroon">
                <input type="radio" name="st_aktif" id="create_st_aktif_true" class="form-check-input" value="T" checked>
                <label class="form-check-label" for="create_st_aktif_true">Ya</label>
              </div>
              <div class="form-check form-check-inline icheck-maroon">
                <input type="radio" name="st_aktif" id="create_st_aktif_false" class="form-check-input" value="F">
                <label class="form-check-label" for="create_st_aktif_false">Tidak</label>
              </div>
            </div>
          </div>

        </form>
      </div> <!-- /.modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="storeTool();">Simpan</button>
      </div> <!-- /.modal-footer -->
    </div> <!-- /.modal-content -->
  </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->