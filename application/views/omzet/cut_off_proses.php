

<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View All Omzet</title>

        <!-- <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script> -->
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
        <script src="<?php echo base_url() ?>assets/js/script.js"></script>

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
            Tahun
        </div>

        <div class="col-xs-4">
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
                '12'  => 'Desember'               
              );
        ?>
        <div class="col-xs-4">
            <?php echo form_dropdown('bulan', $bulan,'','class="form-control"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Supplier
        </div>

        <div class="col-xs-4">
            <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Group Product
        </div>

        <div class="col-xs-4">
            <?php
                $group=array();
                $group['0']='--';
            ?>
            <?php  echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"'); ?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Tanggal Cut Off
        </div>

        <div class="col-xs-4">
            <input type="text" class = 'form-control' id="datepicker2" name="tanggal" placeholder="" autocomplete="off"  required>
        </div>
    
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">        
            
        </div>
        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <a href="<?php echo base_url()."omzet/export_cut_off/"; ?>   " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        
            <?php echo form_close();?>

        </div>
    </div>

    <hr />
   

   </div>
   <?php $no = 1; ?>
   <div class="row"> 
       <div class="col-xs-12">
           <div class="col-xs-12">
           <table id="myTable" class="table table-striped table-bordered table-hover">     
                   <thead>
                       <tr> 
                           <th width="1"><font size="2px">No</th>              
                           <th width="1"><font size="2px">Kode</th>              
                           <th><font size="2px">Branch Name</th>
                           <th><font size="2px">Sub Branch</th>
                           <th><font size="2px">Omzet Berjalan</th>
                           <th><font size="2px">Tanggal Data</th>
                           <th><font size="2px">Tanggal Upload</th>
                           <th><font size="2px">Cut Off</th>
                           <th><font size="2px">Tahun-Bulan</th>
                           <th><font size="2px">Status Closing</th>
                           <th><font size="2px">Supplier</th>
                           <th><font size="2px">Group</th>
                       </tr>
                   </thead>
                   <tbody>
                   
                       <?php foreach($proses as $omzet) : ?>
                       <tr>
                           <td><font size="2px"><?php echo $no++; ?></td>
                           <td><font size="2px"><?php echo $omzet->kode; ?></td>
                           <td><font size="2px"><?php echo $omzet->branch_name; ?></td>
                           <td><font size="2px"><?php echo $omzet->nama_comp; ?></td>
                           <td><font size="2px"><?php echo number_format($omzet->omzet_berjalan); ?></td>
                           <td><font size="2px"><?php echo $omzet->hrdok; ?></td>
                           <td><font size="2px"><?php echo $omzet->lastupload; ?></td>
                           <td><font size="2px"><?php echo $omzet->tanggal_cut_off; ?></td>
                           <td><font size="2px"><?php echo $omzet->tahun.'-'.$omzet->bulan; ?></td>
                           <td><font size="2px"><?php 
                           if ($omzet->status_closing == 1) {
                               echo "closing";
                           }else{
                               echo "belum closing";
                           }
                           ?></td>
                           <td><font size="2px"><?php echo $omzet->supp; ?></td>
                           <td><font size="2px"><?php echo $omzet->group; ?></td>
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
