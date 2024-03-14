<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>">
    <script type="text/javascript">       
    $(document).ready(function() { 
        $("#supp").click(function(){
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
</head>
<body>

    <?php $no = 1; ?>

    <?php echo form_open($url);?>    
    <?php
        $supplier=array();
        foreach($getSupp->result() as $value)
        {
            $supplier['0']= ' - Pilih Supplier - ';
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>

    <?php 
        $interval=date('Y')-2010;
        $year=array();
        $year['0']=' - Pilih Tahun - ';
        //$year['2019']='2019';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }
    ?>

    <div class="row">        
        <div class="col-xs-12">
            <?php echo br(1); ?>
            <h3><?php echo $page_title; ?><hr />
        </div>
    </div>

    <div class = "row">    
        <div class="col-xs-2">
            Tahun            
        </div>
        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
        </div>
        <div class="col-xs-12">&nbsp;</div>
        <div class="col-xs-2">
            Supplier
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
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

        <div class="col-xs-2">        
            
        </div>
        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>
            <a href="<?php echo base_url()."all_sales/export/". $tahun."/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        

        </div>
    </div>


</div>
<?php $no = 1; ?>
<hr>
<?php 
    echo "<pre>";
    echo "anda memilih | ";
    echo "tahun : ".$tahun." || ";
    echo "supplier : ".$supp." || ";
    echo "group : ".$groupx;
    echo "</pre>";

?>
<hr>
<div class="row">        
        <div class="col-xs-12">
        <div class="col-xs-12">
            <table class="table table table-striped table-bordered table-hover table-sales">
                <thead>
                    <tr>                
                        <th width="1%"><font size="2px">No</font></th>   
                        <th width="3%"><font size="2px">Sub Branch</font></th>
                        <th width="2%"><font size="2px">Jan</font></th>
                        <th width="2%"><font size="2px">Feb</font></th>
                        <th width="2%"><font size="2px">Mar</font></th>
                        <th width="2%"><font size="2px">Apr</font></th>
                        <th width="2%"><font size="2px">Mei</font></th>
                        <th width="2%"><font size="2px">Jun</font></th>
                        <th width="2%"><font size="2px">Jul</font></th>
                        <th width="2%"><font size="2px">Agus</font></th>
                        <th width="2%"><font size="2px">Sep</font></th>
                        <th width="2%"><font size="2px">Okt</font></th>
                        <th width="2%"><font size="2px">Nov</font></th>
                        <th width="2%"><font size="2px">Des</font></th>                                      
                    </tr>
                </thead>
                <tbody><font size="1px">
                </tbody>
                </table>
                    </div>
                    <div class="col-xs-11">&nbsp; </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url('assets/jquery.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.min.js') ?>"></script>

    <script type="text/javascript">
    $(".table-sales").DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?php echo base_url('cSalesOmzet/getOmzet') ?>",
            type:'POST',
        }
    });

    </script>
    
    </body>
</html>