<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View All Omzet</title>

        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        
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
                $("#group").html(data);
                }
            });
        });
    });            
</script>

    <?php echo form_open($url);?>    

    <?php 
        $interval=date('Y')-2010;
        $year=array();
        $year['2019']='2019';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }
    ?>

    <div class="row">        
        <div class="col-xs-16">
            <?php echo br(1); ?>
            <h3><?php echo $page_title; ?></h3><hr />
        </div>
    </div>

    <div class="col-xs-2">
        Tahun
    </div>
    <div class="col-xs-9">
        <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
    </div>
    <div class="col-xs-11">&nbsp;</div>
    <div class="col-xs-2">        
        
    </div>
    <div class="col-xs-9">
        <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
        <?php echo form_close();?>
        <a href="<?php echo base_url()."sales_custom/export_d2_all/" ?>   " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Export All</a> &nbsp;
        <a href="<?php echo base_url()."sales_custom/export_d2_candy/" ?>   " class="btn btn-success" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Export Candy</a> &nbsp;
        <a href="<?php echo base_url()."sales_custom/export_d2_beverages/" ?>   " class="btn btn-default" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Export Beverage</a> &nbsp;
        <a href="<?php echo base_url()."sales_custom/export_d2_jayaagung/" ?>   " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Export JayaAgung</a> &nbsp;
        <a href="<?php echo base_url()."sales_custom/export_d2_intrafood/" ?>   " class="btn btn-success" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Export Intrafood</a> &nbsp;
    </div>
</div>
<?php $no = 1; ?>
<hr>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No</font></th>
                            <th width="1"><font size="2px">NAMA DP</th>
                            <th width="1"><font size="2px">Group</th>
                            <th><font size="2px">Unit JAN</th>
                            <th><font size="2px">Unit FEB</th>
                            <th><font size="2px">Unit MAR</th>
                            <th><font size="2px">Unit APR</th>
                            <th><font size="2px">Unit MEI</th>
                            <th><font size="2px">Unit JUN</th>
                            <th><font size="2px">Unit JUL</th>
                            <th><font size="2px">Unit AGS</th>
                            <th><font size="2px">Unit SEP</th>
                            <th><font size="2px">Unit OKT</th>
                            <th><font size="2px">Unit NOV</th>
                            <th><font size="2px">Unit DES</th>
                            <th><font size="2px">Omzet JAN</th>
                            <th><font size="2px">Omzet FEB</th>
                            <th><font size="2px">Omzet MAR</th>
                            <th><font size="2px">Omzet APR</th>
                            <th><font size="2px">Omzet MEI</th>
                            <th><font size="2px">Omzet JUN</th>
                            <th><font size="2px">Omzet JUL</th>
                            <th><font size="2px">Omzet AGS</th>
                            <th><font size="2px">Omzet SEP</th>
                            <th><font size="2px">Omzet OKT</th>
                            <th><font size="2px">Omzet NOV</th>
                            <th><font size="2px">Omzet DES</th>
                            <th><font size="2px">OT JAN</th>
                            <th><font size="2px">OT FEB</th>
                            <th><font size="2px">OT MAR</th>
                            <th><font size="2px">OT APR</th>
                            <th><font size="2px">OT MEI</th>
                            <th><font size="2px">OT JUN</th>
                            <th><font size="2px">OT JUL</th>
                            <th><font size="2px">OT AGS</th>
                            <th><font size="2px">OT SEP</th>
                            <th><font size="2px">OT OKT</th>
                            <th><font size="2px">OT NOV</th>
                            <th><font size="2px">OT DES</th>
                        </tr>
                    </thead>
                    <tbody>                    
                        <?php foreach($query as $omzet) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                            <td><font size="2px"><?php echo $omzet->nama_comp; ?></td>
                            <td><font size="2px"><?php echo $omzet->nama_group; ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->unit1); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->unit2); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->unit3); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->unit4); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->unit5); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->unit6); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->unit7); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->unit8); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->unit9); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->unit10); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->unit11); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->unit12); ?></td>
                            
                            <td><font size="2px"><?php echo number_format($omzet->omzet1); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->omzet2); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->omzet3); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->omzet4); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->omzet5); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->omzet6); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->omzet7); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->omzet8); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->omzet9); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->omzet10); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->omzet11); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->omzet12); ?></td>

                            <td><font size="2px"><?php echo number_format($omzet->ot1); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->ot2); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->ot3); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->ot4); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->ot5); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->ot6); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->ot7); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->ot8); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->ot9); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->ot10); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->ot11); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->ot12); ?></td>
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
                "ordering": false,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>