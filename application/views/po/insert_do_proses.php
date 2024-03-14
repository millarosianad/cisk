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
    <br><br><br>
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
                            <th width='1%'><center>Kode</font></th>
                            <th width='12%'><center>Nodo</th>
                            <th width='12%'><center>Tgldo</th>
                            <th width='12%'><center>KodeDp</th>
                            <th width='12%'><center>Company</th>
                            <th width='12%'><center>Kodeprod</th>
                            <th width='12%'><center>Namaprod</th>
                            <th width='12%'><center>Qty</th>
                            <th width='12%'><center>Nopo</th>

                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($proses as $x) : ?>
                        <tr>        
                            <td><font size="2"><?php echo $x->kode; ?></font></center></td>   
                            <td><font size="2"><?php echo $x->nodo; ?></td>
                            <td><font size="2"><?php echo $x->tgldo; ?></td>
                            <td><font size="2"><?php echo $x->kodedp; ?></td>
                            <td><font size="2"><?php echo $x->company; ?></td> 
                            <td><font size="2"><?php echo $x->kodeprod_delto; ?></td>           
                            <td><font size="2"><?php echo $x->namaprod; ?></td>
                            <td><font size="2"><?php echo $x->qty; ?></td>
                            <td><font size="2"><?php echo $x->nopo; ?></td>

                
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
                "pageLength": 100,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>

    </body>
</html>