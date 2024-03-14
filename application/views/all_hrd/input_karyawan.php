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

    <?php echo form_open_multipart('all_hrd/proses_input_karyawan/');?>
     
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

        <div class="col-xs-3">NIK (*)</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="nik" placeholder="">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Nama (*)</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="nama" placeholder="">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Tanggal Lahir</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' id="datepicker3" name="tgl_lahir" value="">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Alamat</div>
        <div class="col-xs-5">
            <textarea rows='5' name="alamat" class = 'form-control' placeholder=""></textarea>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Email</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="email" placeholder="">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Kontak</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="kontak" placeholder="">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Scan KTP</div>
        <div class="col-xs-5">
            <input type="file" class = 'form-control' name="userfile" value="">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <!-- batas -->
        <div class="col-xs-11"><hr><br></div>
        <!-- batas -->


        <div class="col-xs-3">Divisi (*)</div>
        <div class="col-xs-5">
            
        <?php
        if(isset($query))
        {
            foreach($query->result() as $value)
            {
                $grup['']= '-- Pilih Divisi --';
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
            <input type="text" class = 'form-control' name="jabatan" placeholder="">
        </div>
        <div class="col-xs-11">&nbsp;</div> 

        <div class="col-xs-3">Tanggal Bergabung</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' id="datepicker" name="tgl_gabung" value="">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Status</div>
        <div class="col-xs-3">
            <?php
            $options = array(
                  '1'  => 'Aktif',
                  '0'  => 'Non Aktif',
            );
            echo form_dropdown('status', $options, '0','class = form-control ');

            ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Tanggal Resign</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' id="datepicker2" name="tgl_resign" value="">
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
            <?php echo form_submit('submit',' - Proses Tambah Karyawan -','class="btn btn-primary"');?>
            <?php echo form_close();?>
        </div>
        <div class="col-xs-2">
            <?php echo "&nbsp; &nbsp; "; ?>
             <button type="button" onclick="window.history.go(-1)" class="btn btn-default">kembali</button>
        </div>  
    </div>
    <br><br><br>
    
    
    </body>
</html>