<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <link rel="stylesheet" href="<?php echo base_url('assets/select2/css/select2.css') ?>"/>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <style>
            body{
                padding: 15px;
            }
        </style>

        <!-- Load SCRIPT.JS which will create datepicker for input field  -->
        <script src="<?php echo base_url() ?>assets/js/script.js"></script>

    </head>
    <body>
    <?php echo form_open_multipart('all_hrd/proses_edit_karyawan/');?>
    <!--<?php $url = base_url()."all_hrd/detail_kary/".$this->uri->segment(3).""; ?>-->

    <div class="row">
            
        <div class="col-xs-16">
            <?php echo br(4); ?>
            <h3><?php echo  $page_title; ?></h3><hr />
        </div>

        <div>
            <font color="red">
                <?php 
                    echo validation_errors(); 
                    echo br(1);
                ?>
            </font>
        </div>

        <?php foreach($query as $x) : ?> 

        <div class="col-xs-3">NIK (*)</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="nik" placeholder="" value="<?php echo $x->nik; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Nama (*)</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="nama" placeholder="" value="<?php echo $x->nama_kary; ?>">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Tanggal Lahir</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' id="datepicker3" name="tgl_lahir" value="<?php echo $x->tgl_lahir; ?>">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Alamat</div>
        <div class="col-xs-5">
            <textarea rows='5' name="alamat" class = 'form-control'><?php echo $x->alamat; ?></textarea>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Email</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="email" placeholder="" value="<?php echo $x->email; ?>">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Kontak</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="kontak" placeholder="" value="<?php echo $x->kontak; ?>">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Scan KTP</div>
        <div class="col-xs-5">
            <?php
            if ($x->foto == '') {
                echo "<i>belum ada foto</i><br>";
            ?>
            <input type="file" class = 'form-control' name="userfile" value="">
            <?php
            }else{
                $images = [
                                'src'   => 'uploads/foto/' . $x->foto,
                                'width' => '100'
                        ];
                echo img($images);
                echo "<br>";
                echo anchor(base_url().'uploads/foto/'.$x->foto, 'download');
            }
            ?>            
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <!-- batas -->
        <div class="col-xs-11"><hr><br></div>
        <!-- batas -->


        <div class="col-xs-3">Divisi (*)</div>
        <div class="col-xs-5">            
            <?php
            if(isset($query_div))
            {
                foreach($query_div->result() as $value)
                {
                    $grup[$value->id]= $value->nama_divisi;
                }            
                echo isset($edit)?form_dropdown('divisi',$grup,$nama_divisi,"class=form-control"):
                form_dropdown('divisi',$grup,'',"class=form-control");
            }            
            ?>        

        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Jabatan</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="jabatan" placeholder="" value="<?php echo $x->jabatan; ?>">
        </div>
        <div class="col-xs-11">&nbsp;</div> 

        <div class="col-xs-3">Tanggal Bergabung</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' id='datepicker' name="tgl_gabung" value="<?php echo $x->tgl_gabung; ?>">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Status</div>
        <div class="col-xs-3">

        <?php
            if(isset($query_sts))
            {
                foreach($query_sts->result() as $value)
                {
                    $sts[$value->id]= $value->nama_status;
                    //echo $value->nama_status;
                }            
                echo isset($edit)?form_dropdown('nama_status',$sts,$nama_status,"class=form-control"):
                form_dropdown('id_status',$sts,'',"class=form-control");
            }            
            ?>         
        
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Tanggal Resign</div>
        <div class="col-xs-3">
        <?php 
            if($x->tgl_resign == '0000-00-00')
            $tgl_resignx = '';
            else{
                $tgl_resignx = $x->tgl_resign;
            } 

        ?>
            <input type="text" class = 'form-control' id='datepicker2' name="tgl_resign" value="<?php echo $tgl_resignx; ?>" placeholder="<?php echo $tgl_resignx; ?>">
        </div>
        <div class="col-xs-11">&nbsp;</div>
        <!-- batas -->
        <div class="col-xs-11"><hr><br></div>
        <!-- batas -->        
    </div>

    <div class="row">
        <div class="col-xs-2">
        </div>
        <div class="col-xs-2">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" onclick="window.history.go(-1)" class="btn btn-default">kembali</button>
        </div>  
    </div>
    <br><br><br>
    
    <?php endforeach; ?>

    </body>
</html>