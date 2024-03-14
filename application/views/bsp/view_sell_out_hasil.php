<!doctype html>
<html>
    <head>
        <title>MPM - BSP</title>
        <style>
            body{
                padding: 15px;
            }
        </style>

        <!-- Load Jquery, Bootstrap, dan DataTables dari CDN -->
        <!-- buka url ini: http://pastebin.com/index/WeaY5Fra -->
        <!-- load Jquery dari CDN -->
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
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
        $no = 1;
    ?>

    
    <div class="row">        
        <div class="col-xs-16">
            <?php echo br(3); ?>
            <h3><?php echo $page_title; ?></h3><hr />
        </div>

        <div class="col-xs-3">
            Silahkan pilih tahun :
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,date('Y'),"class='form-control'");?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Silahkan pilih Unit / Value :
        </div>

        <div class="col-xs-5">
            <div class="form-group">
            <?php        
                //echo form_label(" UNIT/VALUE : ");
                $options=array();
                $options['0']='UNIT';
                $options['1']='VALUE';
                echo form_dropdown('uv', $options, 'UNIT','class="form-control"');
            ?>
            </div>
        </div>

        <div class="col-xs-11">&nbsp;</div>      

        <div class="col-xs-3">
            
        </div>

        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>   
            <a href="<?php echo base_url()."all_bsp/export_sell_out/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        </div>
    <br><hr><br><br><br><br><br><br>

    <?php 

        if ($uv == '0') {
            $uvx = 'unit';
        } else {
            $uvx = 'value';
        }
        echo "<pre>";
        echo "Anda memilih ";
        echo "tahun : ".$tahun." | ";
        echo "unit / value : ".$uvx."<br>";
        echo "</pre>";
        $no = 1;
    ?>


    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No</font></th>
                            <th width="1"><font size="2px">Kode Produk</th>
                            <th width="2"><font size="2px">Nama Produk</th>
                            <th width="1"><font size="2px">Rata</th>                            
                            <th><font size="2px">JAN</th>
                            <th><font size="2px">FEB</th>
                            <th><font size="2px">MAR</th>
                            <th><font size="2px">APR</th>
                            <th><font size="2px">MEI</th>
                            <th><font size="2px">JUN</th>
                            <th><font size="2px">JUL</th>
                            <th><font size="2px">AGS</th>
                            <th><font size="2px">SEP</th>
                            <th><font size="2px">OKT</th>
                            <th><font size="2px">NOV</th>
                            <th><font size="2px">DES</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($hasil as $hasils) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>
                            <td><font size="2px"><?php echo $hasils->kode_bsp; ?></td>
                            <td><font size="2px"><?php echo $hasils->deskripsi; ?></td>
                            <td><font size="2px"><?php echo number_format($hasils->rata); ?></td>
                            <td><font size="2px"><?php echo number_format($hasils->b1); ?></td>
                            <td><font size="2px"><?php echo number_format($hasils->b2); ?></td>
                            <td><font size="2px"><?php echo number_format($hasils->b3); ?></td>
                            <td><font size="2px"><?php echo number_format($hasils->b4); ?></td>
                            <td><font size="2px"><?php echo number_format($hasils->b5); ?></td>
                            <td><font size="2px"><?php echo number_format($hasils->b6); ?></td>
                            <td><font size="2px"><?php echo number_format($hasils->b7); ?></td>
                            <td><font size="2px"><?php echo number_format($hasils->b8); ?></td>
                            <td><font size="2px"><?php echo number_format($hasils->b9); ?></td>
                            <td><font size="2px"><?php echo number_format($hasils->b10); ?></td>
                            <td><font size="2px"><?php echo number_format($hasils->b11); ?></td>
                            <td><font size="2px"><?php echo number_format($hasils->b12); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    
                    </tbody>
                    </table>
        </div> 
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