<?php 
    $group=array();
    $group['0']='--';  
    
    $interval=date('Y')-2015;
    $options=array();
    for($i=1;$i<=$interval;$i++)
    {
        $options[''.$i+2015]=''.$i+2015;
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

    echo form_open($url);  
?>

<div class="card">
    

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Periode Awal</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="periode_1" required />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Periode Akhir</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="periode_2" required />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Parameter</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <div class="checkbox-color checkbox-primary">
                        <input id="checkbox1" type="checkbox"  name="bukan_po" value="1">
                        <label for="checkbox1">
                        hanya tampilkan order belum menjadi po
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <?php
    $this->load->view('templates/layout_button_produk');
    ?>
    <hr>
</div>

</div>