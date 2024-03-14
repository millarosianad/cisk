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
    <!--<?php echo form_open_multipart('all_hrd/proses_input_karyawan/');?>-->  
    <?php $url = base_url()."all_hrd/detail_kary/".$this->uri->segment(3).""; ?>

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
            <input type="text" class = 'form-control' name="nama" placeholder="" value="<?php echo $x->nama_kary; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Tanggal Lahir</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="tgl_lahir" value="<?php echo $x->tgl_lahir; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Alamat</div>
        <div class="col-xs-5">
            <textarea rows='5' name="alamat" class = 'form-control' readonly><?php echo $x->alamat; ?></textarea>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Email</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="email" placeholder="" value="<?php echo $x->email; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Kontak</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="kontak" placeholder="" value="<?php echo $x->kontak; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Scan KTP</div>
        <div class="col-xs-5">
            <?php
            if ($x->foto == '') {
                echo "<i>belum ada foto</i>";
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
            <input type="text" class = 'form-control' name="jabatan" placeholder="" value="<?php echo $x->nama_divisi; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Jabatan</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="jabatan" placeholder="" value="<?php echo $x->jabatan; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div> 

        <div class="col-xs-3">Tanggal Bergabung</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="tgl_gabung" value="<?php echo $x->tgl_gabung; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Status</div>
        <div class="col-xs-3">
        <?php 
            if ($x->status == '1') {
                $sts = 'aktif';
            }else{
                $sts = 'non aktif';
            }
        ?>
            <input type="text" class = 'form-control' name="tgl_gabung" value="<?php echo $sts; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Tanggal Resign</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="tgl_resign" value="<?php echo $x->tgl_resign; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <!-- batas -->
        <div class="col-xs-11"><hr><br></div>
        <!-- batas -->

        
    </div>

    <div class="row">
        <div class="col-xs-2">
            
        </div>
        <div class="col-xs-4">
            <button type="submit" class="btn btn-warning">Export to Excel</button>
            <button type="button" onclick="window.history.go(-1)" class="btn btn-default">kembali</button>
        </div>  
    </div>
    <br><br><br>
    
    <?php endforeach; ?>

    </body>
</html>