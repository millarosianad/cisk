<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View Sales Perhari</title>

        <!-- <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script> -->
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <!-- <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css"> -->

        <!-- <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script> -->
        
        
    
    </head>
    <body>

    <script type="text/javascript">       
        $(document).ready(function() 
        { 
            console.log('b');
            $("#supp").click(function()
            {
                console.log('a');
                $.ajax({
                url:"<?php echo base_url(); ?>omzet/buildgroup",    
                data: {kode_supp: $(this).val()},
                type: "POST",
                success: function(data){
                    $("#group").html(data);
                    }
                });
            });
        });            
    </script>

    <?php echo form_open($url);?>    

    <?php
        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>

    <?php 
        $interval=date('Y')-2013;
        $year=array();
        $year['2020']='2020';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2013]=''.$i+2013;
        }
    ?>

    

    <div class="row">        
        <div class="col-xs-16">
            <?php echo br(3); ?>
            <h3><?php echo $page_title; ?></h3><hr />
        </div>

        <div class="col-md-3">
            <div class="form-group">
                
                
            </div>
        </div>
    </div>

        <div class="col-xs-3">
            Tahun (*)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Bulan 
        </div>
        <?php 
            $bulan = array(
                '1'  => 'Januari',
                '2'  => 'Februari',
                '3'  => 'Maret', 
                 '4'  => 'April',
                '5'  => 'Mei',
                '6'  => 'Juni',
                '7'  => 'Juli',
                '8'  => 'Agustus',
                '9'  => 'September',
                '10'  => 'Oktober',
                '11'  => 'November',
                '12'  => 'Desember 2019'               
              );
        ?>
        <div class="col-xs-5">
            <?php echo form_dropdown('bulan', $bulan,'','class="form-control"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Supplier (*)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
        </div>        
        
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Group Product
        </div>

        <div class="col-xs-5">
                <?php
                    $group=array();
                    $group['0']='--';
                   
                ?>
            <?php  echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"'); ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>
        

        <div class="col-xs-3">
            Group By <?php echo anchor(base_url()."omzet/info", '(lihat detail ?)'); ?>
        </div>

        <div class="col-xs-8">
            <label class="fancy-checkbox">
                <input type="checkbox" name="tipe_1" value="1"><span> Kode produk</span>
            </label>
            <label class="fancy-checkbox">&nbsp;</label>
            <label class="fancy-checkbox">
                <input type="checkbox" name="tipe_2" value="1"><span> Class Outlet</span>
            </label>
            <label class="fancy-checkbox">&nbsp;</label>
            <label class="fancy-checkbox">
                <input type="checkbox" name="tipe_3" value="1"><span> Tipe Outlet</span>
            </label>
        </div>

        
        <div class="col-xs-3"></div>        
        
        <div class="col-xs-11"><br></div>

        <div class="col-xs-3">        
            
        </div>
        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>

        </div>
    </div>
