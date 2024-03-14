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
<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<?php echo form_open($url);?>

<h2>
<br>
<?php echo form_label($page_title);?>
</h2>
<pre>
<i>Data tidak ditemukan</i>
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
                foreach($query->result() as $value)
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