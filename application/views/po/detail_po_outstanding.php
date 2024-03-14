<!doctype html>
<html>
    <head>        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>List Order</title>
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
        <script type="text/javascript">       
        $(document).ready(function() { 
        $("#supp").click(function(){
            $.ajax({
            url:"<?php echo base_url(); ?>omzet/buildgroup",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
        });            
        </script>
    </head>
    <body>
    <br><br><br>
    </div>

    <?php echo form_open($url);?>   

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-11">
            <h3><?php echo br(1).' '.$page_title; ?></h3><hr />
        </div>
    </div>

    <br>

    <div class="row">   
        <div class="col-xs-1"></div>        
        <div class="col-xs-3">
            
        </div>
        <div class="col-xs-4">
            <div class="input-group input-daterange">               
                <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
                <?php echo form_close();?>

                <a href="<?php echo base_url()."all_po/export_detail_po_outstanding_us" ?> " class="btn btn-success" role="button" target="blank">Export Proses to Csv
                </a>

            </div>
        </div>
        
    </div>
</div>
</div>
    <hr>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
            <thead>
            <tr>                
                <th width='15%'><center>Branch</th>
                <th width='10%'><center>SubBranch</th>
                <th width='15%'><center>Company</th>
                <th width='10%'><center>NoPo</th>
                <th width='10%'><center>TglPo</th>
                <th width='10%'><center>Kodeprod</th>
                <th width='10%'><center>UnitPO</th>
                <th width='10%'><center>ValuePO</th>
                <th width='10%'><center>UnitDO</th>
                <th width='10%'><center>ValueDO</th>
                <th width='10%'><center>DO</th>
            </tr>
            </thead>
            <tbody>
                    
            <?php foreach($detail as $x) : ?>
            <tr>          
                <td><font size="2"><?php echo $x->branch_name; ?></td>
                <td><font size="2"><?php echo $x->nama_comp; ?></td>
                <td><font size="2"><?php echo $x->company; ?></td>
                <td><font size="2"><?php echo $x->nopo; ?></td>
                <td><font size="2"><?php echo $x->tglpo; ?></td>
                <td><font size="2"><?php echo $x->kodeprod; ?></td>
                <td><font size="2"><?php echo number_format($x->banyak); ?></td>
                <td><font size="2"><?php echo number_format($x->value_po); ?></td>
                <td><font size="2"><?php echo number_format($x->banyak_do); ?></td>
                <td><font size="2"><?php echo number_format($x->value_do); ?></td>
                <td>
                    <?php
                        echo anchor('all_po/detail_po_outstanding/'.str_replace('/','_',$x->nopo), ' ',array('class' => 'glyphicon glyphicon-menu-hamburger'));
                    ?>  
                </td>
            </tr>
            <?php endforeach; ?>
                    
            </tbody>
                    </table>
                    </div>
                    <div class="col-xs-11">&nbsp; </div>
        </div>
    </div>
    

    <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": true,
                "order": [[ 0, "asc" ]],
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
    </script>
    <script src="<?php echo base_url() ?>assets/js/script.js"></script>
    </body>
</html>