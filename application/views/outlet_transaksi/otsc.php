<?php echo form_open($url);?>

<div class="card">

    <div class="col-lg-12 mb-2">
        <i>Menampilan ot single count di periode :</i> 
    </div>

    <div class="col-lg-7">
        <div class="form-group row">
            <label class="col-lg-3 col-form-label">From</label>
            <div class="col-lg-9">
                <input class="form-control" type="date" name="from_otsc" required />
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="form-group row">
            <label class="col-lg-3 col-form-label">To</label>
            <div class="col-lg-9">
                <input class="form-control" type="date" name="to_otsc" required />
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-3 mb-3">
        <i>tidak memperhitungkan outlet yang pernah bertransaksi di periode (exception): </i>        
    </div>

    <div class="col-lg-7">
        <div class="form-group row">
            <label class="col-lg-3 col-form-label">From</label>
            <div class="col-lg-9">
                <input class="form-control" type="date" name="from_outlet" required />
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="form-group row">
            <label class="col-lg-3 col-form-label">To</label>
            <div class="col-lg-9">
                <input class="form-control" type="date" name="to_outlet" required />
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <hr>
    </div>

    <div class="col-lg-7">
        <div class="form-group row">
            <label class="col-lg-3 col-form-label">Output</label>
            <div class="col-lg-9">
                <label for="output1">
                <input type="radio" id="output1" name="output" value="1" checked>                
                    <img src="<?php echo base_url() ?>assets_new/images/ot with exception.jpg" style="max-width: 100px; max-height:100px;"><br>
                    <a href="#" data-toggle="modal" data-target="#imageModal-1">none breakdown (click here)</a>
                </label>
    <!--             <a href="#" data-toggle="modal" data-target="#imageModal-1">view</a> -->
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="form-group row">
            <label class="col-lg-3 col-form-label"></label>
            <div class="col-lg-9">
                <label for="output2">
                <input type="radio" id="output2" name="output" value="2">                
                    <img src="<?php echo base_url() ?>assets_new/images/ot with exception - breakdown by tanggal.jpg" style="max-width: 100px; max-height:100px;">
                    <br>
                    <a href="#" data-toggle="modal" data-target="#imageModal-2">breakdown by date (click here)</a>
                </label>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="form-group row">
            <label class="col-lg-3 col-form-label"></label>
            <div class="col-lg-9">
                <label for="output3">
                <input type="radio" id="output3" name="output" value="3">                
                    <img src="<?php echo base_url() ?>assets_new/images/ot with exception - breakdown by type.jpg" style="max-width: 100px; max-height:100px;">
                    <br>
                    <a href="#" data-toggle="modal" data-target="#imageModal-3">breakdown by type outlet (click here)</a>
                </label>
            </div>
        </div>
    </div>


    <!-- <div class="col-lg-7">
        <div class="form-group row">
            <label class="col-lg-3 col-form-label">Breakdown</label>
            <div class="col-lg-9">
                <input id="checkbox1" type="checkbox" name="otsc_harian" value="1">
                <label>Per Hari</label>
            </div>
        </div>
    </div> -->

    <div class="col-lg-7">
        <div class="form-group row">            
            <div class="col-lg-3"></div>
            <div class="col-lg-9">
                <?php echo form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>
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

<!-- ---------------- Modal ------------ -->
<?php $this->load->view('outlet_transaksi/modal_otsc');?>
<?php $this->load->view('outlet_transaksi/modal_otsc_harian');?>
<?php $this->load->view('outlet_transaksi/modal_otsc_type');?>