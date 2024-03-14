<!-- Modal -->
<div class="modal fade" id="reimburse" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reimburse Biaya Operasional</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open('biaya_operasional/reimburse');?>
                <br>
                <div class="row">
                    <label class="col-sm-3">Tanggal</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="date" name="from" required />
                    </div>
                    To
                    <div class="col-sm-4">
                        <input class="form-control" type="date" name="to" required />
                    </div>
                </div>
                <br>
                <div align="center">
                    <?= form_submit('submit','tampilkan','class="btn btn-success btn-sm"');?>
                    <?= form_close();?>
                </div>
            </div>
        </div>
    </div>
</div>