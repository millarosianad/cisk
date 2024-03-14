<?php echo form_open($url); 
    $interval=date('Y')-2013;
    $year=array();
    $year['2020']='2020';
    for($i=1;$i<=$interval;$i++)
    {
        $year[''.$i+2013]=''.$i+2013;
    }

    $bulan = array(
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
<div class="card">
    <div class="col-sm-10">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tahun</label>
            <div class="col-sm-10">
                <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
            </div>
        </div>
    </div>

    <div class="col-sm-10">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Bulan</label>
            <div class="col-sm-10">
            <?php echo form_dropdown('bulan', $bulan,'','class="form-control"');?>
            </div>
        </div>
    </div>

    <div class="col-sm-10">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-10">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');
            echo form_close();?>
            </div>
        </div>
    </div>

        
        </div>
    </div>