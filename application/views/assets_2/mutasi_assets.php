<?php echo form_open_multipart('assets_2/save_mutasi_assets/' . $this->uri->segment(3)); ?>

<?php 
$username=array();
foreach($user->result() as $value)
{
    $username[$value->id]= $value->username.' - ('.$value->email.')';
}
?>
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
            <label class="col-sm-2 col-form-label">No. PO</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="np" value="<?php echo $a->no_pengajuan ;?>" readonly required/>
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
                    <input class="form-control" type="text" name="np" value="<?php echo number_format ($a->np);?>" readonly />
                </div>
            </div>
        </div>
    </div>
    
    <hr>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label"><h5>Mutasi</h5></label>
            </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">User</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php
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
                    <?php if($a->no_pengajuan == '0' || $a->no_pengajuan == '' ){?>
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
</div>
</div>
</div>
    <div class="col-xl-12 col-md-12">
        <div class="card latest-update-card">
            <div class="card-header">
            <h5>History Mutasi</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        <li><i class="feather icon-maximize full-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card table-card">
                    <div class="card-block">
                        <div class="col-sm-12">
                            <div class="dt-responsive table-responsive">
                            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                                    <thead>
                                        <tr>                
                                            <th><font size="2px">User</th>
                                            <th><font size="2px">Nama Barang</th>
                                            <th><font size="2px">Tanggal Mutasi</th>
                                            <th><font size="2px">Status</th>
                                            <th><font size="2px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($proses as $a) : ?> 
                                        <tr>                   
                                            <td><font size="2px"><?php echo $a->username; ?></td>
                                            <td><font size="2px"><?php echo $a->namabarang; ?></td>
                                            <td><font size="2px"><?php echo $a->tgl_mutasi; ?></td>
                                            <td><font size="2px"><?php if($a->status){
                                                echo "APPROVE";
                                            }else{
                                                echo "PENDING";
                                            } ?></td>
                                            <td>
                                            <?php if ($a->status == '0' ){?>
                                                <button  type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal<?php echo $a->id_mutasi; ?>">
                                                Edit
                                                </button>

                                                <?php $user=$this->session->userdata('id');
                                                if ($user == '297' || $user == '547'){?>
                                                    <button  type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleAprrov<?php echo $a->id_mutasi; ?>">
                                                    Approve
                                                    </button>
                                                <?php }?>                 
                                            <?php }?>
                                            <a href="<?php echo base_url()."assets/file/bukti_mutasi/".$a->bukti_upload; ?>" class="btn btn-warning btn-sm" role="button" download>Gambar 1</a>
                                            <a href="<?php echo base_url()."assets/file/bukti_mutasi/".$a->bukti_upload2; ?>" class="btn btn-warning btn-sm" role="button" download>Gambar 2</a>
                                            <a href="<?php echo base_url()."assets_2/delete_mutasi/$a->id/$a->id_mutasi"; ?>"class="btn btn-danger btn-sm" role="button">Delete</a>
                                        </td>                     
                                        </tr>
<!-- ------------------------------------------- MODAL EDIT ------------------------------------------------ -->

                                        <div class="modal fade" id="exampleModal<?php echo $a->id_mutasi; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg " role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Mutasi</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <?php echo form_open_multipart("assets_2/edit_mutasi_assets/$a->id/$a->id_mutasi"); ?>
                                                <div class="modal-body">
                                                <div class="col-sm-12">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">User</label>
                                                        <div class="col-sm-9">
                                                            <div class="col-sm-6">
                                                                <?php
                                                                    $username=array();
                                                                    $username[$a->userid]= $a->username.' - ('.$a->email.')';                                                                
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
                                                                <input class="form-control" type="date" name="tm" value="<?php echo $a->tgl_mutasi; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Alasan Mutasi</label>
                                                        <div class="col-sm-9">
                                                            <div class="col-sm-6">
                                                                <input class="form-control" type="text" name="am" value="<?php echo $a->alasan_mutasi; ?>" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Upload 1 </label>
                                                        <div class="col-sm-9">
                                                            <div class="col-sm-6">
                                                            <a href="<?php echo base_url()."assets_2/file/bukti_mutasi/".$a->bukti_upload; ?>" target="_blank"><img src="<?php echo base_url()."assets/file/bukti_mutasi/".$a->bukti_upload; ?>" width='70' height='90'></a>
                                                            <br><font color="red">*barang</font>
                                                                <input type="file" name="file" id="file" class="form-control"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Upload 2</label>
                                                        <div class="col-sm-9">
                                                            <div class="col-sm-6">
                                                            <a href="<?php echo base_url()."assets_2/file/bukti_mutasi/".$a->bukti_upload2; ?>"target="_blank"><img src="<?php echo base_url()."assets/file/bukti_mutasi/".$a->bukti_upload2; ?>" width='70' height='90'></a>
                                                            <br><font color="red">*barcode / serial number</font>
                                                                <input type="file" name="file2" id="file2" class="form-control"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group row">            
                                                        <div class="col-sm-2"></div>
                                                        <div class="col-sm-9">
                                                            <div class="col-sm-6">
                                                                <?php 
                                                                echo form_submit('submit','Simpan', 'class="btn btn-success"');
                                                                echo form_close();
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>

<!-- ------------------------------------------- MODAL APPROV ------------------------------------------------ -->  
                                    <div class="modal fade" id="exampleAprrov<?php echo $a->id_mutasi; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg " role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Approval Mutasi</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <?php echo form_open("assets_2/approv_mutasi_assets/$a->id/$a->id_mutasi"); ?>
                                                <div class="modal-body">
                                                <div class="col-sm-12">
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Alasan Approval</label>
                                                        <div class="col-sm-9">
                                                            <textarea class="form-control" type="text" name="approv" value="<?php echo $a->alasan_approve ?>" ></textarea>
                                                            <br>
                                                            <?PHP
                                                                echo form_submit('submit','Simpan', 'class="btn btn-success btn-sm"');
                                                                echo form_close();
                                                            ?> 
                                                        </div>
                                                    </div>
                                                </div>          
                                            </div>
                                        </div>  
                                    </div>
                                </div>

                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   

