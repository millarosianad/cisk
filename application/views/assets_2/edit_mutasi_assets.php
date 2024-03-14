<?php echo form_open_multipart('assets_2/save_mutasi_assets/' . $this->uri->segment(3)); ?>

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
<?php foreach($asset as $a) : 
    $no_pengajuan = $a->no_pengajuan; ?>
<div class="card">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label"><h5>Edit Mutasi</h5></label>
            </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">User</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php
                        $username=array();
                        foreach($user->result() as $value)
                        {
                            $username[$value->id]= $value->username;
                        }
                    
                        
                        echo form_dropdown('user', $username,'','class="form-control"  id="user"');
                        
                        
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal Mutasi</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="tm" required />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Alasan Mutasi</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <textarea class="form-control" type="text" name="am" required ></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Upload 1 </label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <font color="red">*barang</font>
                     <input type="file" name="file" id="file" class="form-control" required/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Upload 2</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <font color="red">*barcode / serial number</font>
                     <input type="file" name="file2" id="file2" class="form-control" required/>
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
                    <?php if($no_pengajuan == ''){?>
                        <a href = '#' class="btn btn-success">update</a>
                        <font color="red">No. PO harap diisi</font>
                    <?php 
                    }else{
                    echo form_submit('submit','Simpan', 'class="btn btn-success"');
                    echo form_close();
                    }?>
                </div>
            </div>
        </div>
    </div>
</div>