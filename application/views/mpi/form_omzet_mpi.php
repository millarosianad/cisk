<script src="<?php echo base_url() ?>assets/js/script.js"></script>
<?php echo form_open($url);?>
        
 
    <div class="row">        
        <div class="col-xs-11">
            <?php echo br(3); ?>
            <h3><?php echo $page_title; ?></h3><hr />
        </div>
    </div>

    <div class="col-xs-2">
        Custom Periode dari
    </div>

    <div class="col-xs-3">        
        <input type="text" class = 'form-control' id="datepicker2" name="from" placeholder="" autocomplete="off">
    </div>

    <div class="col-xs-1">
        sampai
    </div>
    <div class="col-xs-3">  
        <input type="text" class = 'form-control' id="datepicker" name="to" placeholder="" autocomplete="off">   
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div>

    <div class="col-xs-2">
        Database
    </div>
    <div class="col-xs-3">
    
    <?php 
        $database = array(
            '1'  => 'Lokal MPM',
            '2'  => 'Live MPI (hanya bisa 2 bulan terakhir)',           
            );
    ?>
       <?php echo form_dropdown('database', $database,'','class="form-control"');?>
    </div>

    <div class="col-xs-11">
        &nbsp;
    </div>

    <div class="col-xs-2">
        &nbsp;
    </div>

    <div class="col-xs-3">  
        <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
        <?php echo form_close();?>
    </div>
    
    <div class="col-xs-12">  
        <br><br><hr><br>
    </div>
      
    <div class="col-xs-12">  
        <center><h3>Raw Bulanan</h3></center>
    </div>

    <?php
        $no = 1;
    ?>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px"><center>No</font></th>
                            <th><font size="2px"><center>File</th>
                            <th><font size="2px"><center>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($query as $x) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>
                            <td><font size="2px"><?php echo $x->filename; ?></font></td>
                            <td><center>
                                <?php
                                    if ($x->link <> null) {
                                        echo anchor(base_url().'assets/file/raw_data/mpi/'.$x->link, 'download',"class='btn btn-primary btn-sm'");
                                    }else{
                                        echo "belum ada";
                                    }                                    
                                ?>
                                </center>
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
                "ordering": false,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>