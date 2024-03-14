
<!DOCTYPE html>
<html>
<head>
    <title>Sell Out DP</title>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>">

</head>
<body>
    
<script type="text/javascript">       
      
    $(document).ready(function() { 
        $("#year").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>c_dp/build_namacomp",    
            data: {id_year: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
    });

    $(document).ready(function() { 
        $("#supp").click(function(){
            $.ajax({
            url:"<?php echo base_url(); ?>c_dp/buildgroup",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#group").html(data);
                }
            });
        });
    });          
            
</script>
<h2><?php echo $page_title;?></h2>
<?php echo form_open($url);
?>
<hr>
<div class='row'>
    <div class="col-md-3">
        <div class="form-group">
            <?php
                echo form_label("Tahun : (1)");
                //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
                $interval=date('Y')-2015;
                $options=array();
                $options['0']='- Pilih Tahun -';
                for($i=1;$i<=$interval;$i++)
                {
                    $options[''.$i+2015]=''.$i+2015;
                }
                echo form_dropdown('year', $options, $options['0'],'class="form-control"  id="year"');
            ?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <?php        
                echo form_label("Sub Branch : (2)");
                $options=array();
                $options['0']='- Pilih Sub Branch -';
                echo form_dropdown('nocab', $options, 'ALL','class="form-control" id="subbranch"');
            ?>
        </div>
    </div> 

    <div class="col-md-3">
        <div class="form-group">
            <?php        
                echo form_label("Supplier : (3)");
                $supplier=array();
                foreach($query->result() as $value)
                {
                    $supplier[$value->supp]= $value->namasupp;
                }
                echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');
            ?>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <?php        
                echo form_label("Group Product : (4)");
                $group=array();
                $group['0']='--';
                echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"');
            ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?php        
                echo form_label("UNIT/VALUE : (5)");
                $options=array();
                $options['0']='UNIT';
                $options['1']='VALUE';
                echo form_dropdown('uv', $options, 'UNIT','class="form-control"');
            ?>
        </div>
    </div>

    <div class="col-md-10">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
            <a href="<?php echo base_url()."sell/export/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        </div>
    </div>    
</div>


</div>
<?php $no = 1; ?>
<hr>
<div class="row">        
        <div class="col-xs-12">
        <div class="col-xs-12">
            <table class="table table table-striped table-bordered table-hover table-sell">    
                    <thead>
                        <tr>                
                            <th>No</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>JAN</th>
                            <th>FEB</th>
                            <th>MAR</th>
                            <th>APR</th>
                            <th>MEI</th>
                            <th>JUN</th>
                            <th>JUL</th>
                            <th>AGS</th>
                            <th>SEP</th>
                            <th>OKT</th>
                            <th>NOV</th>
                            <th>DES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($sell_out_dp_hasil as $sell) : ?>
                        <tr>        
                            <td><?php echo $no++; ?></td>               
                            <td><?php echo $sell->kodeprod; ?></td>
                            <td><?php echo $sell->namaprod; ?></td>
                            <td><?php echo number_format($sell->b1); ?></td>
                            <td><?php echo number_format($sell->b2); ?></td>
                            <td><?php echo number_format($sell->b3); ?></td>
                            <td><?php echo number_format($sell->b4); ?></td>
                            <td><?php echo number_format($sell->b5); ?></td>
                            <td><?php echo number_format($sell->b6); ?></td>
                            <td><?php echo number_format($sell->b7); ?></td>
                            <td><?php echo number_format($sell->b8); ?></td>
                            <td><?php echo number_format($sell->b9); ?></td>
                            <td><?php echo number_format($sell->b10); ?></td>
                            <td><?php echo number_format($sell->b11); ?></td>
                            <td><?php echo number_format($sell->b12); ?></td>
                        </tr>
                    <?php endforeach; ?>
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
    $(".table-sell").DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?php echo base_url('sell/get_sell_out_dp') ?>",
            type:'POST',
        }
    });

    </script>


</body>

    </body>
</html>


