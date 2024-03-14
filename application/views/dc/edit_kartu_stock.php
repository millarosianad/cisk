<?php
echo form_open($url);  
foreach($get_ms as $a) : ?>

<div class="card">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Est Sales</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="estimasi_sales" value="<?php echo $a->estimasi_sales; ?>" />
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Stock Ideal</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="stock_ideal" value="<?php echo $a->stock_ideal; ?>" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_submit('submit','Update','class="btn btn-primary"');?>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Kode Produk</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="kodeprod" value="<?php echo $a->kodeprod; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">average</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="average" value="<?php echo $a->rata; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">stock akhir unit</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="average" value="<?php echo $a->stock_akhir_unit; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>   
    
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">GIT</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->git; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Total Stock</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->total_stock; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Sales berjalan</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->sales_berjalan; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Estimasi sales</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->estimasi_sales; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Potensi sales</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->potensi_sales; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Stock berjalan</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->stock_berjalan; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">DOI</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->doi; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Stock Ideal Unit</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->stock_ideal_unit; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Purchase Plan</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->pp; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Estimasi Saldo Akhir</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->estimasi_saldo_akhir; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Estimasi Doi Akhir</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->estimasi_doi_akhir; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Purchase Plan Karton</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->pp_karton; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Purchase Plan Kg</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->pp_kg; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Purchase Plan Volume</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->pp_volume; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <input class="form-control" type="hidden" name="signature" value="<?php echo $a->signature; ?>" readonly/>
    <input class="form-control" type="hidden" name="kode" value="<?php echo $a->kode; ?>" readonly/>

    

<hr>

</div>
<?php 
endforeach; 
?>