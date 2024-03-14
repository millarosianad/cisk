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
    <?php echo form_open_multipart('all_transaction/open_credit_limit_update/' . $this->uri->segment(3)); ?>
    <div class="row">
            
        <div class="col-xs-16">
            <?php echo br(3); ?>
            <h3>Detail Open Credit Limit</h3><hr />

            <?php $url = base_url()."all_transaction/open_credit_limit_update/".$this->uri->segment(3).""; ?>
            

        </div>
        <div>

        </div> 
        <?php foreach($query as $querys) : ?>

        <div class="col-xs-2">No Order</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="id_po" value="<?php echo $querys->id; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">DP / Sub Branch</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="company" value="<?php echo $querys->company; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Tanggal Order</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="tgl_pesan" value="<?php echo $querys->tglpesan; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Supplier</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="supp" value="<?php echo $querys->namasupp; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Tipe PO</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="sn" value="<?php echo $querys->tipe; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">No PO :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="jumlah" value="<?php echo $querys->nopo; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Nilai Order :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="jumlah" value="<?php echo "Rp. ".number_format($querys->value); ?>" readonly>
        </div>     

        <div class="col-xs-11">&nbsp;</div>

        <?php foreach($query_piutang as $querys_piutang) : ?>

        <div class="col-xs-2">Piutang : <br><i>(berdasarkan tgl order)</i></div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="jumlah" value="<?php echo "Rp. ".number_format($querys_piutang->piutang); ?>" readonly>
        </div>

        <?php endforeach; ?>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Piutang Saat Ini :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="jumlah" value="<?php echo "Rp. ".number_format($querys->saldoakhir); ?>" readonly>
        </div> 

        

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Bank Garansi :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="jumlah" value="<?php echo "Rp. ".$querys->bank_garansi; ?>" readonly>
        </div>    
        
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Note ke Bagian PO :</div>
        <div class="col-xs-5">
            <textarea rows='7' name="note_acc" class = 'form-control' ><?php echo $querys->note_acc; ?></textarea>
        </div>
        <div class="col-xs-11">&nbsp;</div>

    </div>

     <div class="row">
        <div class="col-xs-2">
            
        </div>
        <div class="col-xs-2">
            <?php echo form_submit('submit','-- Tambah Note --','class="btn btn-primary"');?>
            <?php echo form_close();?>
        </div>
        <div class="col-xs-1">
             <a href="<?php echo base_url()."all_transaction/open_credit_limit"; ?>  " class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Kembali</a>
        </div>       
    </div>
    <br><br><br>

    <hr>


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