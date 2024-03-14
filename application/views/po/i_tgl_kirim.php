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

    <?php echo form_open('all_po/proses_update_tgl_kirim/'.$this->uri->segment(3));?>
  
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
        <div class="col-xs-16"><br><br>
            <h3>Update Tanggal Kirim</h3><hr />
        </div>
        <font color="red">
            <?php 
                echo validation_errors(); 
            ?>
        </font>

        <div class="col-xs-5">
            
        <?php
            if(isset($query))
            {
                foreach($query_afiliasi->result() as $value)
                {
                    $var[$value->id_afiliasi]= $value->nama_afiliasi;
                }
            
                echo isset($edit)?form_dropdown('afiliasi',$var,$id_afiliasi,"class=form-control"):form_dropdown('afiliasi',$var,'',"class=form-control");
            }        
        ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Tanggal Kirim</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' id="datepicker2" name="tgl_kirim" placeholder="">
        </div>

        <div class="col-xs-3">
            <?php
            if(isset($query))
            {
                foreach($query->result() as $value)
                {
                    $var[$value->id]= $value->ket_status;
                }
            
                echo isset($edit)?form_dropdown('var',$var,$id,"class=form-control"):form_dropdown('var',$var,'',"class=form-control");
            }
            ?><?php echo form_submit('submit','Update','class="btn btn-primary"');?>
        </div>
        
            <?php echo form_close();?>
    </div>

<hr>
    
    <div class="row">
        <div class="col-xs-16">
            <br><br>
        </div>
    </div>

    <?php foreach($getpo as $x) : ?>
        <div class="col-xs-2">No PO :</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="kode" value="<?php echo $x->nopo; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Tipe:</div>
        <div class="col-xs-5">
            <?php 
                if ($x->tipe == "S") {
                    $tipe = "SPK";
                } elseif($x->tipe == "R") {
                    $tipe = "Replineshment";
                } else{
                    $tipe = "Alokasi";
                }
                
            ?> 
            <input type="text" class = 'form-control' name="kode" value="<?php echo $tipe; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Tanggal PO</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="namabarang" value="<?php echo $x->tglpo; ?>" readonly>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Company</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="namabarang" value="<?php echo $x->company; ?>" readonly >
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Email</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="namabarang" value="<?php echo $x->email; ?>" readonly >
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Alamat Kirim</div>
        <div class="col-xs-5">
            <textarea cols= 4 rows=7 class = 'form-control' readonly><?php echo $x->alamat; ?></textarea>
        </div>
        <div class="col-xs-11">&nbsp;</div>
    
        <?php endforeach; ?>

    
    <div class="row"><hr><br>
        <div class="col-xs-8">
            
        <hr><br> <h4><center>Product List</center></h4><br>

    <table class="table table-striped">
    <?php $no = 1; ?>
    <thead class="thead-dark">
        <tr>
        <th scope="col">#</th>
        <th scope="col">Kodeprod</th>
        <th scope="col">Kodeprod_DL</th>
        <th scope="col">Namaprod_DL</th>
        <th scope="col">Unit</th>
        </tr>
    </thead>
    <?php foreach($getproduk as $x) : ?>
    <tbody>
        <tr>
        <th scope="row"><?php echo $no++; ?></th>
        <td><?php echo $x->kodeprod; ?></td>
        <td><?php echo $x->dl_kodeprod; ?></td>
        <td><?php echo $x->dl_description; ?></td>
        <td><?php echo $x->banyak; ?></td>
        </tr>
    </tbody>
    
    <?php endforeach; ?>
    </table>

    </div>
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