<div class="modal fade" id="modalDetailPpTool" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail PP Tool</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> <!-- /.modal-header -->
      <div class="modal-body">
        <div class="form-group row">
          <label for="detail_no_pp" class="col-sm-2 col-form-label">No PP</label>
          <div class="col-sm-4">
            <input type="text" name="no_pp" id="detail_no_pp" class="form-control" readonly>
          </div>
        </div>

        <div class="form-group row">
          <label for="detail_tgl_pp" class="col-sm-2 col-form-label">Tanggal</label>
          <div class="col-sm-4">
            <input type="date" name="tgl_pp" id="detail_tgl_pp" class="form-control" readonly>
          </div>
        </div>

        <div class="form-group row">
          <label for="detail_keterangan" class="col-sm-2 col-form-label">Keterangan</label>
          <div class="col-sm-4">
            <textarea name="keterangan" id="detail_keterangan" class="form-control" rows="3"
              style="resize: none;" readonly></textarea>
          </div>
        </div>

        <table class="table table-bordered table-hover table-sm w-100" id="table-detail">
          <thead>
            <tr>
              <th class="text-center align-middle" style="width: 50px;">No</th>
              <th class="text-center align-middle" style="width: 25%;">Kode Tool</th>
              <th class="text-center align-middle">Nama Tool</th>
              <th class="text-center align-middle" style="width: 15%;">Jenis Tool</th>
              <th class="text-center align-middle" style="width: 10%;">Qty</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

      </div> <!-- /.modal-body -->
    </div> <!-- /.modal-content -->
  </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->