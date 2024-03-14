<?php echo form_open($url);?>
<div class="card-block">
    <div>
        <div class="form-group row">
            <label class="col-form-label">Periode</label><br>
            <div class="col-8">
                <div class="col-12">
                    <input class="form-control" type="date" name="periode" required />
                    <br>
                    <?php echo form_submit('submit','Proses','class="btn btn-primary btn-round btn-sm"');?>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>
</div>