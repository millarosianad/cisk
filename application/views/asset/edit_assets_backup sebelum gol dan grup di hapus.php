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

    
   
    <?php echo form_open('all_assets/proses_update_assets/' . $this->uri->segment(3)); ?>
    <?php //echo form_label($page_title);?>
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
            <h3>Detail Asset</h3><hr />

            <?php $url = base_url()."all_assets/detail_assets/".$this->uri->segment(3).""; ?>

            <a href="<?php echo base_url()."all_assets/view_assets"; ?>  " class="btn btn-primary" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Kembali</a>

            <a href="<?php echo base_url()."all_assets/qrcode/".$this->uri->segment(3).""; ?>  " target="_blank" class="btn btn-success" role="button"><span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span> Generate QR Code</a>
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
        <?php foreach($assets as $asset) : ?>
        <div class="col-xs-2">No Voucher :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="kode" value="<?php echo $asset->kode; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Nama Barang</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="namabarang" value="<?php echo $asset->namabarang; ?>" >
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">S/N</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="sn" value="<?php echo $asset->sn; ?>" >
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Jumlah Barang :</div>
        <div class="col-xs-1">
            <input type="text" class = 'form-control' name="jumlah" value="<?php echo $asset->jumlah; ?>" >
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Untuk :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="untuk" value="<?php echo $asset->untuk; ?>" >
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Tanggal Payroll</div>
        <div class="col-xs-3">
            <input type="text" class="form-control" id="datepicker" name="tglperol" value="<?php echo $asset->tglperol; ?>" >
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Golongan</div>
        <div class="col-xs-2">    
        <?php
            $gol=array('0.25'=>'GOL I','0.125'=>'GOL II','0.0625'=>'GOL III','0.05'=>'GOL IV','0'=>'GOL V');
            echo isset($edit)?form_dropdown('gol',$gol,$golongan,"class=form-control"):form_dropdown('gol',$gol,'',"class=form-control");
        ?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Group Asset</div>
        <div class="col-xs-3">
        
    <?php
    if(isset($query))
    {
        foreach($query->result() as $value)
        {
            $grup[$value->id]= $value->namagrup;
        }
    
        echo isset($edit)?form_dropdown('grup',$grup,$grupid,"class=form-control"):form_dropdown('grup',$grup,'',"class=form-control");

    }
    
    ?>

    </div>
    <div class="col-xs-11">&nbsp;</div>


        <div class="col-xs-2">Nilai Perolehan :</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="np" value="<?php echo $asset->np; ?>" >
        </div>
        <div class="col-xs-11">&nbsp;</div>
        </div>

        <hr><br>
    
    <div class="row">
        <div class="col-xs-2">Nilai Jual :</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="nj" value="<?php echo $asset->nj; ?>" >
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Tanggal Jual :</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' id="datepicker2" name="tgljual" value="<?php echo $asset->tgljual; ?>" >
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Deskripsi Jual :</div>
        <div class="col-xs-5">
            <textarea rows='7' name="deskripsi" class = 'form-control' ><?php echo $asset->deskripsi; ?></textarea>
        </div>
        <div class="col-xs-11">&nbsp;</div>

    </div>
    <?php endforeach; ?>

    <div class="col-xs-2">
            <?php echo form_submit('submit','Update','class="btn btn-primary"');?>
            <?php echo form_close();?>
        </div>

    
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