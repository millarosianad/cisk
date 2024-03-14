<?php
echo form_open($url);  
foreach($proses as $a) : ?>

<div class="card">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Kode Produk</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="kodeprod" value="<?php echo $a->kodeprod; ?>" readonly />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nama Produk</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="namaprod" value="<?php echo $a->namaprod; ?>" readonly />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Kode Produk Deltomed</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="kodeprod_deltomed" value="<?php echo $a->kodeprod_deltomed; ?>" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Berat Kg (karton) </label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="berat" value="<?php echo $a->berat; ?>" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Volume M3 (kubikasi) </label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="volume" value="<?php echo $a->volume; ?>" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Satuan terkecil </label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="satuan" value="<?php echo $a->satuan; ?>" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>

<hr>

</div>
<?php 
endforeach; 
?>