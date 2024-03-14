<?php
echo form_open($url);
?>

    <div class="card">
        <div class="col-lg-12">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">From</label>
                <div class="col-lg-9">
                    <input class="form-control" type="date" name="from" id="From" required />
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">To</label>
                <div class="col-lg-9">
                    <input class="form-control" type="date" name="to" id = "to" required />
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label"></label>
                <div class="col-lg-9">                
                    <?php echo form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary" id="btnKirim"');?>                    
                    <button class="btn btn-primary" id="btnLoading" type="button" disabled>
                    <!-- <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> -->
                    ... mohon tunggu ...
                    </button>

                   
                    <?php echo form_close();?>
                </div>
            </div>
        </div>

    <hr>
    <?php
    $this->load->view('templates/layout_button_produk');
    ?>
    <hr>
</div>

</div>

<script>
    $(document).ready(function() {
        $("#btnLoading").hide();
    });
</script>