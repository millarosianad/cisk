<!doctype html>
<html>
    <head>
        <title>MPM</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
    <?php //echo br(3); ?>
    <?php echo form_open($url);?>
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

    <?php
        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
        //echo form_dropdown('supp', $options, 'All',"class='form-control'");
    ?>


    <?php
        $wilayah = array(
                  '1'  => 'Seluruh Wilayah',
                  '2'  => 'DP Barat',
                  '3'  => 'DP Timur',                  
                );
    ?>

    <div class="row">        
        <div class="col-xs-16">
            <?php echo br(3); ?>
            <h3>Data Omzet DP Wilayah Timur</h3><hr />
        </div>

        <div class="col-xs-2">
            Silahkan pilih tahun :
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,date('Y'),"class='form-control'");?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Silahkan pilih supplier :
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,date('Y'),"class='form-control'");?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-5">            
            <?php
                $data = array(
                  'menuid'  => $getmenuid
                );
            echo form_hidden($data);
            //echo "menuid di view : ".$getmenuid;
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