<?php
echo form_open($url);  
foreach($get_est_sales as $a) : ?>

<div class="card">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Est Sales</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="est_sales" value="<?php echo $a->est_sales; ?>" />
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">stock level</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="stock_level" value="<?php echo $a->stock_level; ?>" />
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">git</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="git" value="<?php echo $a->git; ?>"/>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Branch</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="branch_name" value="<?php echo $a->branch_name; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">SubBranch</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="nama_comp" value="<?php echo $a->nama_comp; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">kodeprod</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="kodeprod" value="<?php echo $a->kodeprod; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">namaprod</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="namaprod" value="<?php echo $a->namaprod; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">average</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="average" value="<?php echo $a->average; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">stock on hand</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="stock_on_hand" value="<?php echo $a->stock_on_hand; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">total stock</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="total_stock" value="<?php echo $a->total_stock; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">estimasi saldo berjalan</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="est_saldo_berjalan" value="<?php echo $a->est_saldo_berjalan; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">stock level in unit</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="stock_level_unit" value="<?php echo $a->stock_level_unit; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">estimasi saldo akhir</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="est_saldo_akhir" value="<?php echo $a->est_saldo_akhir; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">estimasi doi akhir</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="est_doi_akhir" value="<?php echo $a->est_doi_akhir; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">purchase plan</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="purchase_plan" value="<?php echo $a->purchase_plan; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">purchase plan in karton</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="pp_in_karton" value="<?php echo $a->pp_in_karton; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">purchase plan in kg</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="pp_in_kg" value="<?php echo $a->pp_in_kg; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">value</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="value" value="<?php echo number_format($a->pp_in_value); ?>" readonly/>
                </div>
            </div>
        </div>
    </div>

    <input class="form-control" type="hidden" name="kode" value="<?php echo $a->kode; ?>" readonly/>
    <input class="form-control" type="hidden" name="created_date" value="<?php echo $a->created_date; ?>" readonly/>
    <input class="form-control" type="hidden" name="supp" value="<?php echo $a->supp; ?>" readonly/>

    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_submit('submit','Update Estimasai Sales','class="btn btn-primary"');?>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>

<hr>

</div>
<?php 
endforeach; 
?>