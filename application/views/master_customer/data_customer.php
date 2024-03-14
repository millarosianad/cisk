<?php echo form_open($url.'1'); ?>
<pre>
    (*) untuk sementara belum bisa memproses data dengan tahun yang berbeda
</pre>
    <div class="card">

        <div class="col-lg-12">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">From</label>
                <div class="col-lg-9">
                    <input class="form-control" type="date" name="from" required />
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
                    <?php echo form_submit('submit','Proses','onclick="return x();" class="btn btn-primary" id="btnKirim"');?>
    
                    <button class="btn btn-primary" id="btnLoading" type="button" disabled>
                    <!-- <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> -->
                    ... mohon tunggu ...
                    </button>

                   
                    <?php echo form_close();?>
                </div>
            </div>
        </div>

    </div>   

    <script>
    function x(){        
            $("#btnLoading").show();        
            $("#btnKirim").hide();        
    }

    $(document).ready(function() {
        
        $("#btnLoading").hide();
    });


    </script>
