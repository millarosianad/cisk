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

        </div> 
        <?php foreach($assets as $asset) : ?>

        <div class="col-xs-2">No Voucher :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="kode" value="<?php echo $asset->kode; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Nama Barang</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="namabarang" value="<?php echo $asset->namabarang; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">S/N</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="sn" value="<?php echo $asset->sn; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Jumlah Barang :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="jumlah" value="<?php echo $asset->jumlah; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Untuk :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="untuk" value="<?php echo $asset->untuk; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Tanggal Payroll</div>
        <div class="col-xs-5">
            <input type="text" class="form-control" name="tglperol" value="<?php echo $asset->tglperol; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Golongan</div>
        <div class="col-xs-5">    
        <?php

            $golongan = $asset->gol ;
            //echo "golongan :".$golongan;
            if ($golongan == '0.25') {
                $gol = 'GOL I';
            }elseif ($golongan == '0.125') {
                $gol = 'GOL II';
            }elseif ($golongan == '0.0625') {
                $gol = 'GOL III';
            }elseif ($golongan == '0.05') {
                $gol = 'GOL IV';
            }elseif ($golongan == '0') {
                $gol = 'GOL V';
            }else{
                $gol = 'Tidak diketahui';
            }
        ?>
        <input type="text" class="form-control" name="gol" value="<?php echo $gol; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

    <div class="col-xs-2">Group Asset</div>
    <div class="col-xs-5">
        
    <input type="text" class="form-control" name="gol" value="<?php echo $asset->namagrup; ?>" readonly>
    </div>
    <div class="col-xs-11">&nbsp;</div>


        <div class="col-xs-2">Nilai Perolehan :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="np" value="<?php echo "Rp. ".number_format($asset->np) ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>
        </div>

        <hr><br>
    
    <div class="row">
        <div class="col-xs-2">Nilai Jual :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="nj" value="<?php echo "Rp. ".number_format($asset->nj) ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Tanggal Jual :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="tgljual" value="<?php echo $asset->tgljual; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Deskripsi Jual :</div>
        <div class="col-xs-5">
            <textarea rows='7' name="deskripsi" class = 'form-control' readonly><?php echo $asset->deskripsi; ?></textarea>
        </div>
        <div class="col-xs-11">&nbsp;</div>

    </div>
    <?php endforeach; ?>

    
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