<!-- Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Search Asset</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php echo form_open($url);?>
      <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">From</label>
            <div class="col-sm-4">
                <input class="form-control" type="date" name="from"  required />
            </div>To
            <div class="col-sm-4">
                <input class="form-control" type="date" name="to" required />
            </div>
            <br>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Data</label>
            <div class="col-sm-4">
                <select name="data_table" class="form-control" >
                    <option value="1">Kas</option>
                    <option value="2">Jurnal</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">No. Voucher / Jurnal</label>
            <div class="col-sm-6">
                <input class="form-control" type="text" name="nv"  />
            </div>
            <br>
            <?php echo form_submit('submit','Seacrh', 'class="btn btn-warning btn-sm"');?>
            <?php echo form_close();?>
        </div>
      </div>
    </div>
  </div>
</div>