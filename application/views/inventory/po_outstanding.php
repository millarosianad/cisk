<?php 
    echo form_open($url);  

    // echo $get_supp;
    // die;
?>

<div class="card">

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Principal</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                <?php 
                    $jenis_data = array(
                        '001'=>'Deltomed',     
                        '005'=>'Ultra Sakti',                   
                        '012'=>'Intrafood',                   
                        '002'=>'Marguna',                   
                        '004'=>'Jaya Agung (sedang dikembangkan)',                   
                        '013'=>'Strive (sedang dikembangkan)',                   
                        '014'=>'Hni (sedang dikembangkan)',                   
                    );
                    $supplier=array();
                    foreach($get_supp->result() as $value)
                    {
                        $supplier[$value->supp]= $value->namasupp;
                    }
                    ?>
                    <?php echo form_dropdown('supp',$supplier,'','class="form-control"');?>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input id="checkbox1" type="checkbox"  name="po_update" value="1">
                    <label>PO Terupdate</label>
                </div>
            </div>
        </div>
    </div> -->

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
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>

    <!-- <hr>
    <div class="col-sm-12">
        <div class="form-group row">
            <u class="col-sm-12" style="color:red; font-weight: bold;"> Note : Jika PO Terupdate di ceklis, maka yang ditarik hanya data 2 bulan terakhir</u>
        </div>
    </div> -->
    
</div>

</div>







