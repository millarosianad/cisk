<?php echo form_open($url); ?>

<?php
    foreach ($client as $a) {
        $company = $a->company;
        $npwp = $a->npwp;
        $email = $a->email;
    }

    
?>
<h4><b><?php echo $page_title; ?></h4>
<hr>
<div class="col-sm-12">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Pelanggan</label>
        <div class="col-sm-10">
            <input class="form-control" value="<?php echo $company; ?>" type="text" name="np" required readonly />
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">NPWP</label>
            <div class="col-sm-10">
                <input class="form-control" value="<?php echo $npwp; ?>" type="text" name="npwp" required readonly/>
            </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input class="form-control" value="<?php echo $email; ?>" type="text" name="email" required readonly/>
            </div>
    </div>
</div>

<h5 class="col-sm-2"><b>Pilih Alamat</h5>
<div class="col-sm-12">
    <div class="form-group row">
        <?php
            foreach ($client as $a) {
                $alamat = $a->alamat;
                $kode_alamat = $a->kode_alamat;
                ?>
                    <div class="col-sm-6">
                        <div class="radio radiofill radio-inline">
                            <label>
                            <input type='radio' name='kode_alamat' value='<?php echo $kode_alamat; ?>' id='kode_alamat' class='checked' required/><i class="helper"></i> <?php echo $alamat; ?>
                            </label>
                        </div>
                    </div>
            <?php
            }    
        ?>
    </div>
</div>
<div class="col-sm-2">
<?php echo form_submit('submit','Simpan', 'class="btn btn-success"');?>
<?php echo form_close();?>
</div>
</div>  