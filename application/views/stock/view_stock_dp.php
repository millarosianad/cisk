<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View Stock DP</title>
        <!-- Load Jquery, Bootstrap, dan DataTables dari CDN -->
        <!-- buka url ini: http://pastebin.com/index/WeaY5Fra -->
        <!-- load Jquery dari CDN -->
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

<script type="text/javascript">       
    $(document).ready(function() { 
        $("#year").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>all_stock/build_namacomp",    
            data: {id: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
    });
            
</script>

<?php echo form_open($url);?>
<h2>
<?php echo form_label($page_title);?>
</h2><hr />

<?php echo br(2);?>
<div class='row'>

    <div class="col-md-3">
        <div class="form-group">
            <?php
                echo form_label(" Silahkan Pilih Tahun : ");
                //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
                $interval=date('Y')-2010;
                $options=array();
                $options['0']='- Pilih Tahun -';
                $options['2010']='2010';
                for($i=1;$i<=$interval;$i++)
                {
                    $options[''.$i+2010]=''.$i+2010;
                }
                echo form_dropdown('year', $options, $options['0'],'class="form-control"  id="year"');
            ?>
        </div>
    </div>

    
    <div class="col-md-3">
        <div class="form-group">
            <?php        
                echo form_label("Silahkan Pilih Sub Branch : ");
                $options=array();
                $options['0']='- Pilih Sub Branch -';
                echo form_dropdown('nocab', $options, 'ALL','class="form-control" id="subbranch"');
            ?>
        </div>
    </div>     
    



    
     <div class="col-md-3">
        <div class="form-group">
            <?php        
                echo form_label("Silahkan Pilih UNIT/VALUE : ");
                $options=array();
                $options['0']='UNIT';
                $options['1']='VALUE';
                echo form_dropdown('uv', $options, 'UNIT','class="form-control"');
            ?>
        </div>
    </div>
    





    <div class="col-md-4">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?> ||    
            <a href="<?php echo base_url()."all_stock/export_stok_dp/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a> 
        </div>
    </div> 


</div>

<br />

<?php echo form_close();?>

<?php

if ($uv == '0') {
    $uvx = 'unit';
} else{
    $uvx = 'value';
}
    echo "<pre>";
    echo "Menampilkan data sbb,  ";
    echo "<b>Sub Branch</b> : ".$kode;
    echo " || ";
    echo "<b>Tahun</b> : ".$year;
    echo " || ";
    echo "<b>Unit / Value</b> : ".$uvx;
    //echo " || ";


    echo "</pre>";
?>
<hr>


<?php $no = 1; ?>
<div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No</th>
                            <th width="1"><font size="2px">Kode Produk</th>
                            <th width="1"><font size="2px">Nama Produk</th>
                            <th width="1"><font size="2px">AVG (6 bln)</th>
                            <th width="1"><font size="2px">Jan</th>
                            <th width="1"><font size="2px">Feb</th>
                            <th width="1"><font size="2px">Mar</th>
                            <th width="1"><font size="2px">Apr</th>
                            <th width="1"><font size="2px">Mei</th>
                            <th width="1"><font size="2px">Jun</th>
                            <th width="1"><font size="2px">Jul</th>
                            <th width="1"><font size="2px">Ags</th>
                            <th width="1"><font size="2px">Sep</th>
                            <th width="1"><font size="2px">Okt</th>
                            <th width="1"><font size="2px">Nov</th>
                            <th width="1"><font size="2px">Des</th>
                            <th width="1"><font size="2px">DOI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($query3 as $query) : ?>
                        <tr>        
                            <td width="1"><font size="2px"><center><?php echo $no++; ?></center></td>               
                            <td width="1"><font size="2px"><?php echo $query->kodeprod; ?></td>
                            <td width="1"><font size="2px"><?php echo $query->namaprod; ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->rata); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->b1); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->b2); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->b3); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->b4); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->b5); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->b6); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->b7); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->b8); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->b9); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->b10); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->b11); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->b12); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->doi); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    </table>
        </div> 
    </div>

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                
            });
        });
        </script>

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