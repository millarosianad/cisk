<!doctype html>
<html>
    <head>        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>List Order</title>
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
                $("#subbranch").html(data);
                }
            });
        });
        });            
        </script>
    </head>
    <body>
    <br>
    </div>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-11">
            <h3><?php echo br(1).' '.$page_title; ?></h3><hr />
        </div>
    </div>
    <?php echo form_open_multipart($url);?>
    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-1"></div>
        <div class="col-xs-5">
            
        </div>
    </div><br>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-1">Pilih FIle</div>
        <div class="col-xs-5">
            <input type="file" name="file" id="file" class="form-control" placeholder="file">
        </div>
    </div><br>

    <div class="row">   
        <div class="col-xs-1"></div>
        <div class="col-xs-1"></div>
        <div class="col-xs-5">            
            
            <?php echo form_submit('submit','submit','class="btn btn-primary"')?>
            <?php echo form_close(); ?>
            <a href="<?php echo base_url()."retur/transfer_retur/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> Transfer ke Retur <span class="glyphicon glyphicon-transfer" aria-hidden="true"></span></a>
        
        </div>
        
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
                            <th width="1"><font size="2px">Supp</th>
                            <th><font size="2px">Tgldo</th>
                            <th><font size="2px">userid</th>
                            <th><font size="2px">kodeprod</th>
                            <th><font size="2px">banyak</th>
                            <th><font size="2px">nodo</th>
                            <th><font size="2px">nodo_beli</th>
                            <th><font size="2px">noseri</th>
                            <th><font size="2px">noseri_beli</th>
                            <th><font size="2px">nopo</th>
                            <th><font size="2px">tglbuat</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($query as $a) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                            <td><font size="2px"><?php echo $a->supp; ?></td>
                            <td><font size="2px"><?php echo $a->tgldo; ?></td>
                            <td><font size="2px"><?php echo $a->userid; ?></td>
                            <td><font size="2px"><?php echo $a->kodeprod; ?></td>
                            <td><font size="2px"><?php echo $a->banyak; ?></td>
                            <td><font size="2px"><?php echo $a->nodo; ?></td>
                            <td><font size="2px"><?php echo $a->nodo_beli; ?></td>
                            <td><font size="2px"><?php echo $a->noseri; ?></td>
                            <td><font size="2px"><?php echo $a->noseri_beli; ?></td>
                            <td><font size="2px"><?php echo $a->nopo; ?></td>
                            <td><font size="2px"><?php echo $a->tglbuat; ?></td>
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