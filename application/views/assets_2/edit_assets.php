<?php echo form_open_multipart('assets_2/update_assets/' . $this->uri->segment(3)); ?>
<div class="col-xs-16">
    <?php $url = base_url()."assets_2/detail_assets/".$this->uri->segment(3).""; ?>
    <a href="<?php echo base_url()."assets_2/view_assets"; ?>  " class="btn btn-primary" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Kembali</a>
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
            <label class="col-sm-2 col-form-label">S/N</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="sn" value="<?php echo $a->sn ;?>"/>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nilai Perolehan</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="np" value="<?php echo ($a->np);?>"/>
                </div>
            </div>
        </div>
    </div>
    
    <hr>
    
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">No. PO</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php 
                    // echo "xxxx : ".$a->no_pengajuan;
                    // die; 
                    if ($a->no_pengajuan == '0' || $a->no_pengajuan == '' ){
                        $nopo=array();
                        $nopo['Hanya Mutasi']='Hanya Mutasi';
                        foreach($no_po->result() as $value)
                        {
                            $nopo[$value->no_po]= "$value->no_po - $value->username";
                        }
                    }else{
                        $nopo=array();
                        $nopo[$a->no_pengajuan]=$a->no_pengajuan;
                        foreach($no_po->result() as $value)
                        {
                            $nopo[$value->no_po]= "$value->no_po - $value->username";
                        }
                    }
                    echo form_dropdown('nopo', $nopo,'','class="form-control"  id="user"');
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Nilai Jual</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="number" name="nj"value="<?php echo $a->nj;?>" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal Jual</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <?php if ($a->tgljual == '1970-01-01'){
                    $tgl_jual = '';
                }else{
                    $tgl_jual = "$a->tgljual";
                }
                ?>
                    <input class="form-control" type="date" name="tj" value="<?php echo $tgl_jual;?>" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Deskripsi</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <textarea rows='7' name="deskripsi" class = 'form-control' ><?php echo $a->deskripsi;?> </textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Upload Faktur (<font color="red">*PDF</font>)</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php if($a->upload_faktur != ''){
                        echo anchor("assets_2/file/faktur_asset/".$a->upload_faktur, 'Lihat Faktur', "class='btn btn-primary btn-sm'");
                        echo '<br><br>';
                    } ?>
                     <input type="file" name="file" id="file" class="form-control" />
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

    

