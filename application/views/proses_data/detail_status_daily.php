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

    <?php echo form_open('all_status_proses_data/proses_input_daily/');?>

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
            <h3>Penginputan Status Proses Data Afiliasi</h3><hr />
        </div>
        <div class="col-xs-16">    
            <a href="<?php echo base_url()."all_status_proses_data/view_status"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> View Status Proses Data</a>
            <hr />
        </div>
        <div>
        <font color="red">
            <?php 
                echo validation_errors(); 
                echo br(1);
            ?>
        </font>
        </div> 

        <div class="col-xs-2">Nama Afiliasi</div>
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

        <div class="col-xs-2">Tanggal Data</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' id="datepicker2" name="tgldata" placeholder="masukkan tanggal data">
        </div>
        <div class="col-xs-11">&nbsp;</div>


        <div class="col-xs-2">Status</div>
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
        
        ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">Keterangan</div>
        <div class="col-xs-5">
            <textarea rows='7' name="keterangan" class = 'form-control' placeholder="masukkan keterangan"></textarea>
        </div>
        <div class="col-xs-11">&nbsp;</div>


    </div>

        <div class="col-xs-2">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>
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