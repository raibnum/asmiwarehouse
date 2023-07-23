<div class="modal fade" id="modalEditPpTool" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form PP Tool</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> <!-- /.modal-header -->
      <div class="modal-body">
        <form id="form-edit">
          @method('put')

          <div class="form-group row">
            <label for="edit_no_pp" class="col-sm-2 col-form-label">No PP</label>
            <div class="col-sm-4">
              <input type="text" name="no_pp" id="edit_no_pp" class="form-control"  readonly>
            </div>
          </div>

          <div class="form-group row">
            <label for="edit_tgl_pp" class="col-sm-2 col-form-label">Tanggal</label>
            <div class="col-sm-4">
              <input type="date" name="tgl_pp" id="edit_tgl_pp" class="form-control">
            </div>
          </div>

          <div class="form-group row">
            <label for="edit_keterangan" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-4">
              <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="3"
                style="resize: none;"></textarea>
            </div>
          </div>

          <table class="table table-bordered table-hover table-sm w-100" id="table-edit">
            <thead>
              <tr>
                <th class="text-center align-middle" style="width: 50px;">No</th>
                <th class="text-center align-middle" style="width: 25%;">Kode Tool</th>
                <th class="text-center align-middle">Nama Tool</th>
                <th class="text-center align-middle" style="width: 15%;">Jenis Tool</th>
                <th class="text-center align-middle" style="width: 10%;">Qty</th>
                <th class="text-center align-middle" style="width: 50px;">
                  <button type="button" class="btn btn-xs btn-tool text-success" onclick="addRowEdit();">
                    <i class="fas fa-plus"></i>
                  </button>
                </th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

        </form>
      </div> <!-- /.modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updatePpTool();">Simpan</button>
      </div> <!-- /.modal-footer -->
    </div> <!-- /.modal-content -->
  </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->