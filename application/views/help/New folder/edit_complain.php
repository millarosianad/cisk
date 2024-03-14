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

    <?php echo form_open('all_help/proses_update_complain/' . $this->uri->segment(3)); ?>

    <div class="row">
            
        <div class="col-xs-16">
            <?php echo br(3); ?>
            <h3>Complain System - Update</h3><hr />

            <?php $url = base_url()."all_help/detail_complain/".$this->uri->segment(3).""; ?>
        </div>
        
        <div>
            <font color="red">
                <?php 
                    echo validation_errors(); 
                    echo br(1);
                ?>
            </font>
        </div>

        <?php foreach($helps as $help) : ?>

        <div class="col-xs-2">ID Complain</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="user" value="<?php echo $this->uri->segment(3); ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Nama User / Pelapor</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="user" value="<?php echo $help->nama_pelapor; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Alamat Email Pelapor</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="email" value="<?php echo $help->email_pelapor; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Alamat Kontak Pelapor</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="email" value="<?php echo $help->kontak_pelapor; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Tanggal Pengajuan</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="sn" value="<?php echo $help->tgl_ajuan; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Kategori Permasalahan :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="jumlah" value="<?php echo $help->nama_kategori; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Detail Permasalahan :</div>
        <div class="col-xs-5">
            <textarea rows='7' name="deskripsi" class = 'form-control' readonly><?php echo $help->masalah; ?></textarea>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Gambar / File Pendukung :</div>
        <div class="col-xs-5">
            <?php  

                $images = [
                                'src'   => 'uploads/' . $help->file,
                                'width' => '100'
                        ];

                echo img($images);

            ?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

    </div>
    <hr>
    <div class="row">

        <div class="col-xs-2">Status Permasalahan</div>
        <div class="col-xs-5">

        <?php
        if(isset($flag))
        {
            foreach($flag->result() as $value)
            {
                $id_status[$value->id]= $value->nama_status;
            }
        
            echo isset($edit)?form_dropdown('id_status',$id_status,$nama_status,"class=form-control"):
            form_dropdown('id_status',$id_status,'',"class=form-control");
        }
        
        ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>
            
        <div class="col-xs-2">Nama IT</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="nama_it" value="<?php echo $help->nama_it; ?>">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Solusi</div>
        <div class="col-xs-5">
            <textarea rows='7' name="solusi" class = 'form-control'><?php echo $help->solusi; ?></textarea>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Tanggal Selesai <i>(Perkiraan Tanggal Selesai)</i></div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' id="datepicker2" name="tgl_selesai" value="<?php echo $help->tgl_selesai; ?>">
        </div>
        <div class="col-xs-11">&nbsp;</div>

    </div>
    <?php endforeach; ?>
    <br>
    <div class="row">
        <div class="col-xs-2">
            
        </div>
        <div class="col-xs-2">
            <?php echo form_submit('submit','-- Update Data Complain --','class="btn btn-primary"');?>
            <?php echo form_close();?>
        </div>
        <div class="col-xs-2">
            <?php echo "&nbsp; &nbsp; "; ?>
             <a href="<?php echo base_url()."all_help/view_complain"; ?>  " class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>  Kembali</a>
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