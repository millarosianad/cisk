<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<!-- Load Datatables dan Bootstrap dari CDN -->
<!-- <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>

<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css"> -->

<!-- Load SCRIPT.JS which will create datepicker for input field  -->        
<script src="<?php echo base_url() ?>assets/js/script.js"></script>

<?php 
    echo form_open($url.$grup_lang.'/'.$jenis_faktur);
?>
<br>

<input type="hidden" class = 'form-control' name="from" placeholder="" value="<?php echo $from; ?>">
<input type="hidden" class = 'form-control' name="to" placeholder="" value="<?php echo $to; ?>">

<div class="dataTables_scroll">
    <div class="dataTables_scrollBody" style="position: relative; overflow: auto; max-height: 350px; width: 100%;"><table id="basic-scroller" class="table table-striped table-bordered nowrap dataTable no-footer" role="grid" aria-describedby="basic-scroller_info" style="width: 100%; position: absolute; top: 0px; left: 0px;">
        <thead>
            <tr>                
                <th width="1"><font size="2px">No</th>
                <th width="1"><font size="1px">
                <input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" >
                </th>
                <th><font size="2px">No. Faktur</th>
                <th><font size="2px">No. Sales</th>
            </tr>
        </thead>
                                
        <tbody>
            <tr role="row" class="odd">
            <?php 
                $no = 1;
                foreach($kode->result() as $value) : ?>
                    <tr>                  
                        <td><?php echo $no++?></td>
                        <td>
                        <center>
                            <input type="checkbox" id="<?php echo $value->nomor ?>" name="options[]" value="<?php echo $value->nodokjdi ?>">
                        </center>
                        </td>    
                        <td><?php echo $value->nomor?></td>
                        <td><?php echo $value->no_sales?></td>
                    </tr>
            <?php endforeach; ?>
        </tr>
        </tbody></table>
        <div style="position: relative; top: 0px; left: 0px; width: 1px; height: 500px;"></div>
    </div>
</div>

<div class='row'>
    <div class='col-md-3'>    
        <?php
            echo form_label("Keterangan : ");
            $ketdd=array('Copy Faktur'=>'Copy Faktur','Faktur Lunas'=>'Faktur Lunas');
            echo form_dropdown('keterangan',$ketdd,'','class="form-control"');
        ?> 
    </div>
    <div class='col-md-3'>    
        <?php
        echo br(1);
            echo form_submit('submit','Tambah','class="btn btn-primary"');
        ?>
    </div>
</div>

<?php echo form_close();?>