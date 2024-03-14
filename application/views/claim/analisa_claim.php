</div>
<!DOCTYPE html>
<html>
<head>
    <title>Log Claim</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>"> 
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
        <?php 
            $interval=date('Y')-2018;
            $year=array();
            $year['2020']='2020';
            for($i=1;$i<=$interval;$i++)
            {
                $year[''.$i+2018]=''.$i+2018;
            }
        ?>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="col-xs-16">            
                    <h3><br><br><?php echo $page_title; ?></h3><hr />
                </div>
                <br>
            </div>
        </div>
        <?php echo form_open_multipart($url);?>
        <div class="row">
            <div class="col-md-11">
                <div class="col-xs-3">            
                    Prinsipal
                </div>
                <div class="col-xs-5">   

                <!-- <?php 
                    foreach($query['data'] as $a) : ?>
                    <?php
                        echo $a['NAMASUPP'];
                    ?>
                <?php endforeach; ?> -->
 
                <?php $jenis_data=array(
                        '001'=>'Deltomed',
                        '002'=>'Marguna',
                        '005'=>'Ultra Sakti'                        
                    );?>
                    <?php echo form_dropdown('supp',$jenis_data,'','class="form-control"');?>

                </div>
                <div class="col-xs-3">            
                    &nbsp;
                </div>                
            </div>
        </div><br>



        <div class="row">
            <div class="col-md-11">
                <div class="col-xs-3">            
                    Periode
                </div>
                <div class="col-xs-5">   

                    <div class="input-group input-daterange">
                        <input type="text" class = 'form-control' id="datepicker2" name="periode1" placeholder="" autocomplete="off">
                        <div class="input-group-addon">to</div>
                            <input type="text" class = 'form-control' id="datepicker" name="periode2" placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>         
        </div><br>
        
        <div class="row">
            <div class="col-xs-11">
                <div class="col-xs-3">     
                </div>
                <div class="col-xs-5">  
                    <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
                    <?php echo form_close();?>
                </div>
            </div>
        </div><br>


        <script src="<?php echo base_url() ?>assets/js/script.js"></script>
</body>
</html>