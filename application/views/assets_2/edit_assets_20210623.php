<?php echo form_open('assets/update_assets/' . $this->uri->segment(3)); ?>
<?php $url = base_url()."assets/detail_assets/".$this->uri->segment(3).""; ?>
<div class="col-xs-16">
    <?php $url = base_url()."assets/detail_assets/".$this->uri->segment(3).""; ?>
    <a href="<?php echo base_url()."assets/view_assets"; ?>  " class="btn btn-primary" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Kembali</a>
    <hr>
</div>
<div>
    <font color="red">
        <?php 
            echo validation_errors(); 
            echo br(1);
        ?>
    </font>
</div> 
<?php foreach($asset as $a) : ?>
<div class="card">

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">No Voucher</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="nv" value="<?php echo $a->kode ;?>" readonly />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nama Barang</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="nb" value="<?php echo $a->namabarang ;?>" readonly />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">S/N</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="sn" value="<?php echo $a->sn ;?>" readonly />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Jumlah Barang</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="jb" value="<?php echo $a->jumlah ;?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Keperluan :</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="kpr" value="<?php echo $a->untuk ;?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal Payroll</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="tp" value="<?php echo $a->tglperol ;?>" readonly />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nilai Perolehan</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="np" value="<?php echo $a->np;?>" readonly />
                </div>
            </div>
        </div>
    </div>
    
    <hr>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nilai Jual</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="nj" required/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal Jual</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="tj" required/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Deskripsi</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                     <textarea rows='7' name="deskripsi" class = 'form-control' required></textarea>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_submit('submit','update', 'class="btn btn-success"');?>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>

    

