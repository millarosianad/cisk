<!doctype html>
<html>
    <head>
        <title>codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <link rel="stylesheet" href="<?php echo base_url('assets/select2/css/select2.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
    
    <?php //echo br(3); ?>
    <?php echo form_open('all_report/data_po_hasil/');?>
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
            <h3>Laporan PO</h3><hr />
        </div>

        <div class="col-xs-2">
            Silahkan pilih tahun :
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,date('Y'),"class='form-control'");?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Silahkan pilih bulan :
        </div>

        <div class="col-xs-5">
            
        <?php
            $options = array(
                  '01'  => 'Januari',
                  '02'    => 'Februari',
                  '03'   => 'Maret',
                  '04' => 'April',
                  '05'  => 'Mei',
                  '06'    => 'Juni',
                  '07'   => 'Juli',
                  '08' => 'Agustus',
                  '09'  => 'September',
                  '10'    => 'Oktober',
                  '11'   => 'November',
                  '12' => 'Desember',
                );

            //$shirts_on_sale = array('small', 'large');

            echo form_dropdown('bulan', $options, 'large', "class='form-control'");

?>

        </div>

        <div class="col-xs-11">&nbsp;</div>

        

        <div class="col-xs-2">

        
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>
        </div>
    </div>
        
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