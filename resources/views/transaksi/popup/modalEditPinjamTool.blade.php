<div class="modal fade" id="modalEditPinjamTool" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form Edit Pinjam Tool</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> <!-- /.modal-header -->
      <div class="modal-body">
        <form id="form-edit">
          @method('put')

          <div class="form-group row">
            <label for="edit_kd_pinj" class="col-sm-2 col-form-label">Kode</label>
            <div class="col-sm-4">
              <input type="text" name="kd_pinj" id="edit_kd_pinj" class="form-control" placeholder="Kode Pinjam" readonly required>
            </div>
          </div>

          <div class="form-group row">
            <label for="edit_tgl" class="col-sm-2 col-form-label">Tanggal</label>
            <div class="col-sm-4">
              <input type="date" name="tgl" id="edit_tgl" class="form-control" required readonly>
            </div>
            <div class="col-sm-2">
              <input type="time" name="jam" id="edit_jam" class="form-control" required readonly>
            </div>
          </div>

          <div class="form-group row">
            <label for="edit_operator" class="col-sm-2 col-form-label">Operator</label>
            <div class="col-sm-4">
              <select name="operator" id="edit_operator" class="form-control" required disabled>
                <option></option>
                @foreach ($opt_operator as $operator)
                <option value="{{ $operator->id }}">{{ $operator->nm_operator . ' # ' . $operator->divisi }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <table class="table table-bordered table-hover table-sm w-100" id="table-edit">
            <thead>
              <tr>
                <th class="text-center align-middle" style="width: 50px;">No</th>
                <th class="text-center align-middle" style="width: 20%;">Tool</th>
                <th class="text-center align-middle">Nama</th>
                <th class="text-center align-middle" style="width: 20%;">Jenis</th>
                <th class="text-center align-middle" style="width: 15%;">Qty</th>
                <th class="text-center align-middle" style="width: 50px;">
                  <div class="icheck-maroon my-1">
                    <input type="checkbox" name="check_all" id="check_all" value="T">
                    <label for="check_all"></label>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

        </form>
      </div> <!-- /.modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updatePinjamTool();">Simpan</button>
      </div> <!-- /.modal-footer -->
    </div> <!-- /.modal-content -->
  </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->