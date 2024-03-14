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

    <?php echo form_open_multipart('all_hrd/proses_input_cuti/');?>
     
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

        <div class="col-xs-3">Nama Karyawan (*)</div>
        <div class="col-xs-5">
            
        <?php
        if(isset($query_kary))
        {
            foreach($query_kary->result() as $value)
            {
                $grup['y']= '-- Pilih Karyawan --';
                $grup[$value->nik]= $value->nama_kary.' -- '.$value->nama_divisi;
            }
        
            echo isset($edit)?form_dropdown('nik',$grup,$nama_kary,"class=form-control"):
            form_dropdown('nik',$grup,'',"class=form-control");
        }
        
        ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Bulan (*)</div>
        <div class="col-xs-3">
            <?php
            $options = array(
                  'test'  => '-- Pilih Bulan -- ',
                  '1'     => 'Januari',
                  '2'     => 'Februari',
                  '3'     => 'Maret',
                  '4'     => 'April',
                  '5'     => 'Mei',
                  '6'     => 'Juni',
                  '7'     => 'Juli',
                  '8'     => 'Agustus',
                  '9'     => 'September',
                  '10'     => 'Oktober',
                  '11'     => 'November',
                  '12'     => 'Desember'
            );

            //$shirts_on_sale = array('small', 'large');
            echo form_dropdown('bulan', $options, '0','class = form-control ');

            ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Jenis Cuti (*)</div>
        <div class="col-xs-3">
            <?php
            if(isset($query_cuti))
            {
                foreach($query_cuti->result() as $value)
                {
                    $x['test']= '-- Pilih Cuti --';
                    $x[$value->id]= $value->jenis_cuti;
                }
            
                echo isset($edit)?form_dropdown('jenis_cuti',$x,$jenis_cuti,"class=form-control"):
                form_dropdown('jenis_cuti',$x,'',"class=form-control");
            }
            
            ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Tanggal Cuti (*)</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' id="datepicker" name="tgl_cuti" value="">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Status Potong Cuti Tahunan (*)</div>
        <div class="col-xs-3">
            <?php
            $options = array(
                  'test'  => '-- Pilih Status -- ',
                  '1'     => 'Potong Cuti Tahunan',
                  '2'     => 'Tidak Potong',
            );

            //$shirts_on_sale = array('small', 'large');
            echo form_dropdown('status', $options, '0','class = form-control ');

            ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Keterangan (*)</div>
        <div class="col-xs-5">
            <textarea rows='5' name="keterangan" class = 'form-control' placeholder=""></textarea>
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
            <?php echo form_submit('submit',' - Proses Tambah Cuti -','class="btn btn-primary"');?>
            <?php echo form_close();?>
        </div>
        <div class="col-xs-2">
            <?php echo "&nbsp; &nbsp; "; ?>
             <a href="<?php echo base_url()."all_hrd/view_cuti"; ?>  " class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>  Kembali</a>
        </div>  
    </div>
    <br><br><br>
    
    
    </body>
</html>