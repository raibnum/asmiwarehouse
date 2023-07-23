<div class="modal fade" id="modalCreatePpTool" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form PP Tool</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> <!-- /.modal-header -->
      <div class="modal-body">
        <form id="form-create">

          <div class="form-group row">
            <label for="create_no_pp" class="col-sm-2 col-form-label">No PP</label>
            <div class="col-sm-4">
              <input type="text" name="no_pp" id="create_no_pp" class="form-control" value="{{ $no_pp }}" readonly>
            </div>
          </div>

          <div class="form-group row">
            <label for="create_tgl_pp" class="col-sm-2 col-form-label">Tanggal</label>
            <div class="col-sm-4">
              <input type="date" name="tgl_pp" id="create_tgl_pp" class="form-control">
            </div>
          </div>

          <div class="form-group row">
            <label for="create_keterangan" class="col-sm-2 col-form-label">Keterangan</label>
            <div class="col-sm-4">
              <textarea name="keterangan" id="create_keterangan" class="form-control" rows="3"
                style="resize: none;"></textarea>
            </div>
          </div>

          <table class="table table-bordered table-hover table-sm w-100" id="table-create">
            <thead>
              <tr>
                <th class="text-center align-middle" style="width: 50px;">No</th>
                <th class="text-center align-middle" style="width: 25%;">Kode Tool</th>
                <th class="text-center align-middle">Nama Tool</th>
                <th class="text-center align-middle" style="width: 15%;">Jenis Tool</th>
                <th class="text-center align-middle" style="width: 10%;">Qty</th>
                <th class="text-center align-middle" style="width: 50px;">
                  <button type="button" class="btn btn-xs btn-tool text-success" onclick="addRowCreate();">
                    <i class="fas fa-plus"></i>
                  </button>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr id="row-create-1">
                <td class="text-center">1</td>
                <td>
                  <select name="kd_tool[]" class="form-control form-control-sm select2-tool" onchange="autoFillTool(this); checkDuplicateTool(this);" required>
                    <option></option>
                    @foreach ($opt_tool as $opt)
                      <option value="{{ $opt->kd_tool }}" data-nm_tool="{{ $opt->nm_tool }}" data-jenis="{{ $opt->jenisTool->nm_jenis }}">{{ $opt->kd_tool }}</option>
                    @endforeach
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
                  <button type="button" class="btn btn-xs btn-tool text-danger" onclick="deleteRowCreate(1);" disabled>
                    <i class="fas fa-minus"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

        </form>
      </div> <!-- /.modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="storePpTool();">Simpan</button>
      </div> <!-- /.modal-footer -->
    </div> <!-- /.modal-content -->
  </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->