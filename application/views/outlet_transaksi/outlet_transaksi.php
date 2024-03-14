<?php echo form_open($url);

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
            <label class="col-sm-2 col-form-label">Breakdown</label>
            <div class="col-sm-10">        

                <div class="checkbox-color checkbox-primary">
                    <input id="checkbox1" type="checkbox"  name="tipe_1" value="1">
                    <label for="checkbox1">
                        Class Outlet
                    </label>
                </div>

                <div class="checkbox-color checkbox-primary">
                    <input id="checkbox2" type="checkbox"  name="tipe_2" value="1">
                    <label for="checkbox2">
                    Tipe Outlet
                    </label>
                </div>

                <div class="checkbox-color checkbox-primary">
                    <input id="checkbox3" type="checkbox"  name="tipe_3" value="1">
                    <label for="checkbox3">
                    Kode Produk
                    </label>
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