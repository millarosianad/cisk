<!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

<?php 
    $interval=date('Y')-2010;
    $year=array();
    $year['2018']='2018';
    for($i=1;$i<=$interval;$i++)
    {
        $year[''.$i+2010]=''.$i+2010;
    }
?>
<?php
    $month = array(
      '1,2,3,4,5,6,7,8,9,10,11,12'  => '-All Bulan-',
      '01'  => 'Januari',
      '02'  => 'Februari',
      '03'  => 'Maret',  
      '04'  => 'April',
      '05'  => 'Mei',
      '06'  => 'Juni',
      '07'  => 'Juli',
      '08'  => 'Agustus',
      '09'  => 'September',
      '10'  => 'Oktober',
      '11'  => 'November',
      '12'  => 'Desember'               
    );
?>

<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<?php echo form_open($url);?>

<h2>
<br>
<?php echo form_label($page_title);?>
</h2>
<pre>
<?php    
    foreach($query_dp->result() as $value)
    {
        echo " ".$value->grup_nama." (".$tahun."-".$bulan.")";
    }

?>
</pre>
<hr />

<div class='row'>

    <div class="col-md-2">
        <div class="form-group">
            <?php
                echo form_label("Tahun (1) : ");
                echo form_dropdown('tahun', $year,'','class="form-control"');
            ?>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <?php
                echo form_label("Bulan (2) : ");
                echo form_dropdown('bulan', $month,'',"class='form-control'");
            ?>
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            <?php
                echo form_label("DP (3) : ");
                foreach($query_pel->result() as $value)
                {
                    $x[$value->grup_lang]= $value->grup_nama;                                       
                }
                echo form_dropdown('grup_lang',$x,'','class="form-control" id="grup_lang"');
                //echo $x;
            ?>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
        </div>
    </div>         
</div>
<hr>
<?php echo form_close();?>

<?php echo form_open($url2);?>

<div class='row'>
    <div class='col-md-10'>
<?php
    echo form_label("List DO :  ");
    $supp='';
    echo '<table class="table table-hover">';
    $count=1;

    foreach($query->result() as $value)
    {     
        if($count%6!=0)
        {
        ?>
        <td>
           <div class="checkbox-inline">
           <input name="options[]" type="CHECKBOX" id="" value="<?php echo $value->nodokacu ?>"><?php echo $value->nodokacu ?>
           </div>
        </td>        
        <?php
        }
        else
        {
        ?>
            <td>
                <div class="checkbox-inline">
                    <input name="options[]" type="CHECKBOX" id="" value="<?php echo $value->nodokacu; ?>"><?php echo $value->nodokacu ?>
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
            echo form_submit('submit','Sudah Terima','class="btn btn-primary"');
        ?>
    </div>
</div>
<br><br><br>

<?php echo form_close();?>

<hr>
<?php $no = 1 ; ?> 
    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                <thead>
                    <tr>                
                        <th>No</th>
                        <th>Do</th>
                        <th>Tahun</th>
                        <th>Bulan</th>
                        <th><center>Grup Lang</center></th>
                        <th><center>Created</center></th>    
                        <th><center>Deleted</center></th>     
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tampil as $tampil_temps) : ?>
                    <tr>        
                        <td width="5%"><?php echo $no; ?></td>
                        <td width="25%"><?php echo $tampil_temps->do; ?></td>
                        <td width="10%"><?php echo $tampil_temps->tahun; ?></td>
                        <td width="10%"><?php echo $tampil_temps->bulan; ?></td>
                        <td width="10%"><?php echo $tampil_temps->grup_lang; ?></td>
                        <td width="10%"><?php echo $tampil_temps->created; ?></td>
                        <td><center>
                            <?php
                                echo anchor('all_surat_jalan/delete_do/' . $tampil_temps->id_rekap_do, ' ',"class='glyphicon glyphicon-remove'");
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

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": false,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>