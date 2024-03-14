<?php 
echo form_open_multipart($url); 

$tahun = array(
    // '1'  => 'Januari',
    // '2'  => 'Februari',
    // '3'  => 'Maret', 
    // '4'  => 'April',
    // '5'  => 'Mei',
    // '6'  => 'Juni',
    // '7'  => 'Juli',
    // '8'  => 'Agustus',
    // '9'  => 'September',
    '2021'  => '2021',
    // '11'  => 'November',
    // '12'  => 'Desember'               
  );

$bulan = array(
    // '01'  => 'Januari',
    // '02'  => 'Februari',
    '03'  => 'Maret', 
    // '04'  => 'April',
    // '05'  => 'Mei',
    // '06'  => 'Juni',
    // '07'  => 'Juli',
    // '08'  => 'Agustus',
    // '09'  => 'September',
    // '10'  => 'Oktober',
    // '11'  => 'November',
    // '12'  => 'Desember'               
  );

  $subbranch = array(
    'SBMS2'  => 'Bangka Belitung + belitung (sumber makmur)',
    'SBJS7'  => 'Palembang',
    'SBTB5'  => 'Baturaja',
    // '12'  => 'Desember'               
  );

    $kode=array();
    foreach($get_subbranch->result() as $value)
    {
        $kode[$value->kode]= $value->nama_comp;
    }

?>
<div class="card">

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Sub Branch</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <!-- <?php echo form_dropdown('subbranch', $subbranch,'','class="form-control"');?> -->
                    <?php echo form_dropdown('subbranch', $kode,'','class="form-control"');?>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Stock untuk tahun</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_dropdown('tahun', $tahun,'','class="form-control"');?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Stock untuk bulan</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_dropdown('bulan', $bulan,'','class="form-control"');?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Upload (.xls)</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input type="file" name="file" class="form-control" required>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>

</div>




</div>







