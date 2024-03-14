<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<!-- Load Datatables dan Bootstrap dari CDN -->
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>

<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

<!-- Load SCRIPT.JS which will create datepicker for input field  -->        
<script src="<?php echo base_url() ?>assets/js/script.js"></script>

<?php echo form_open($url1);?>
<h2>
<?php echo form_label($page_title);?>
</h2>
<hr />

<div class='row'>
    <div class="col-md-5">
        <div class="form-group">
            <?php
                echo form_label("Silahkan Pilih Client : ");
                foreach($query->result() as $value)
                {
                    $x[$value->grup_lang]= $value->grup_nama;                                       
                }
                echo form_dropdown('grup_lang',$x,'','class="form-control" id="grup_lang"');
                //echo $x;
            ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?php echo form_label("Tampilkan Jenis Faktur : "); ?>
            <?php $ketdd=array(
                    1=>'Faktur Lunas',
                    0=>'Copy Faktur',
                    2=>'Seluruh Faktur'
                );?>
                <?php echo form_dropdown('keterangan',$ketdd,'','class="form-control"');?>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
        </div>
    </div>         
</div>

<?php echo form_close();?>

<?php echo form_open($url2);?>

<div class='row'>
    <div class='col-md-10'>
<?php
    echo form_label("Silahkan Pilih Faktur No : ");
    $supp='';
    echo '<table class="table table-hover">';
    $count=1;

    foreach($kode->result() as $value)
    {     

        if($count%6!=0)
        {
        ?>
        <td>
           <div class="checkbox-inline">
           <input name="options[]" type="CHECKBOX" id="" value="<?php echo $value->nodokjdi ?>"><?php echo $value->nomor ?>
           </div>
        </td>
        <!-- <?php //echo $value->supp."[]" ?> -->
        
        <?php
        }
        else
        {
            ?>
            <td>
                <div class="checkbox-inline">
                    <input name="options[]"" type="CHECKBOX" id="" value="<?php echo $value->nodokjdi ?>"><?php echo $value->nomor ?>
                </div>
            </td>
        <?php
            echo '</tr><tr>';
        }
        $count++;
    }
    echo '</tr></table>';
?>

    </div>
</div>
<hr>
<div class='row'>
    <div class='col-md-3'>    
        <?php
            echo form_label("Keterangan : ");
            $ketdd=array('Faktur Lunas'=>'Faktur Lunas','Copy Faktur'=>'Copy Faktur');
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

<hr>
<?php $no = 1 ; ?> 
    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                <thead>
                    <tr>                
                        <th>No</th>
                        <th>No Faktur</th>
                        <th>Rp</th>
                        <th>No Faktur Pajak</th>
                        <th><center>Keterangan</center></th>
                        <th><center>No Sales</center></th>
                        <th><center>Delete</center></th>              
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tampil_temp as $tampil_temps) : ?>
                    <tr>        
                        <td width="5%"><?php echo $no; ?></td>
                        <td width="25%"><?php echo $tampil_temps->faktur; ?></td>
                        <td width="10%"><?php echo number_format($tampil_temps->nildok); ?></td>
                        <td width="10%"><?php echo $tampil_temps->pajak; ?></td>
                        <td width="10%"><?php echo $tampil_temps->keterangan; ?></td>
                        <td width="10%"><?php echo $tampil_temps->no_sales; ?></td>
                        <td><center>
                            <?php
                                echo anchor('all_surat_jalan/delete_temp/' . $tampil_temps->id, ' ',"class='glyphicon glyphicon-remove'");
                            ?>
                            </center>                           
                        </td>        
                    </tr>
                    <?php $no++; ?>
                
                <?php endforeach; ?>
                </tbody>
            </table>
        </div> 
    </div>

    <div class='row'>
        <div class='col-md-5'>
            <div class='form-inline'>
                <?php
                //echo "<br>uri 3 : ".$url3;
                echo form_open($url3);
                echo form_label('Tanggal : ','datepicker');
                echo form_input('tanggal','','id="datepicker" class="form-control"');
                echo form_submit('submit','SAVE','class="form-control"');
                echo form_close();?>
                <br><br><br>
            </div>
       </div>
    </div>