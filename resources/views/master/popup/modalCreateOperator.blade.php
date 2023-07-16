<div class="modal fade" id="modalCreateOperator" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Operator</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> <!-- /.modal-header -->
      <div class="modal-body">
        <form id="form-create">

          <div class="form-group row">
            <label for="create_nm_operator" class="col-sm-2 col-form-label">Nama</label>
            <div class="col-sm-10">
              <input type="text" name="nm_operator" id="create_nm_operator" class="form-control" placeholder="Nama Operator" maxlength="100">
            </div> <!-- /.col -->
          </div>

          <div class="form-group row">
            <label for="create_divisi" class="col-sm-2 col-form-label">Divisi</label>
            <div class="col-sm-10">
              <select name="divisi" id="create_divisi" class="form-control select2">
                <option></option>
                @foreach ($divisi as $div)
                <option value="{{ $div }}">{{ $div }}</option>
                @endforeach
              </select>
            </div>
          </div>

        </form>
      </div> <!-- /.modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="storeOperator();">Simpan</button>
      </div> <!-- /.modal-footer -->
    </div> <!-- /.modal-content -->
  </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->