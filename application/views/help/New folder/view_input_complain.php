<!doctype html>
<html>
    <head>
        <title>codeigniter crud generator</title>
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

        <script type="text/javascript">
        function onlyNumbers(event)
        {
            var e = event || evt; // for trans-browser compatibility
            var charCode = e.which || e.keyCode;

            if ((charCode < 48 || charCode > 57) && (charCode < 37 || charCode>40) && (charCode < 8 || charCode >8) && (charCode < 46 || charCode > 46) )
                    return false;
                 return true;

        }
        </script>
    </head>
    <body>

    <?php echo form_open_multipart('all_help/proses_input_complain/');?>

    <?php 
        //echo form_label(" Year : ");
        //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
        $interval=date('Y')-2010;
        $year=array();
        $year['2010']='2010';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }
        //echo br(5);
        //echo form_dropdown('tahun', $year,date('Y'),"class='form-control'");
    ?>            
    <div class="row">
            
        <div class="col-xs-16">
            <?php echo br(3); ?>
            <h3>Complain System - Input</h3><hr />
        </div>

        <div>
        <font color="red">
            <?php 
                echo validation_errors(); 
                echo br(1);
            ?>
        </font>
        </div> 

        <div class="col-xs-3">Nama User / Pelapor (wajib diisi)</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="user" placeholder="..">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Alamat Email (wajib diisi)</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="email" placeholder="..">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">No kontak yang dapat dihubungi (wajib diisi)</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="kontak" placeholder="..">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Kategori Permasalahan (wajib diisi)</div>
        <div class="col-xs-5">
            
        <?php
        if(isset($query))
        {
            foreach($query->result() as $value)
            {
                $grup[$value->id]= $value->nama_kategori;
            }
        
            echo isset($edit)?form_dropdown('grup',$grup,$nama_kategori,"class=form-control"):
            form_dropdown('grup',$grup,'',"class=form-control");
        }
        
        ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>   

        <div class="col-xs-3">Detail Permasalahan (wajib diisi)</div>
        <div class="col-xs-5">
            <textarea rows='7' name="masalah" class = 'form-control' placeholder="masukkan detail permasalahan"></textarea>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Gambar / File Pendukung (Opsional)</div>
        <div class="col-xs-5">
            <input type="file" class = 'form-control' name="userfile" value="">
        </div>
        <div class="col-xs-11">&nbsp;</div>
    </div>

    <div class="row">
        <div class="col-xs-2">
            
        </div>
        <div class="col-xs-2">
            <?php echo form_submit('submit',' - Proses Tiket Pengajuan -','class="btn btn-primary"');?>
            <?php echo form_close();?>
        </div>
        <div class="col-xs-2">
            <?php echo "&nbsp; &nbsp; "; ?>
             <a href="<?php echo base_url()."all_help/view_complain_user"; ?>  " class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>  Kembali</a>
        </div>  
    </div>
    <br><br><br>
    
    <div class="row">
        <!--jquery dan select2-->
        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/select2/js/select2.min.js') ?>"></script>
        <script>
            $(document).ready(function () {
                $(".select2").select2({
                    placeholder: "Please Select"
                });
            });
        </script>
    </body>
</html>