<?php
    $get_supp = $this->input->get('supp');
    // var_dump($get_supp);
    $supplier=array();
    foreach($supp as $value)
    {   
        $supplier['999'] = '- PILIH -';
        $supplier[$value->supp]= $value->namasupp;
    }
?>

<form action="<?= base_url().'transaction/list_product';?>" method="get">
<div class="row">
    <div class="col-6">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Supplier</label>
            <div class="col-sm-10">
                <?=form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group row">
            <div class="col-sm-10">
                <button class="btn btn-warning">Pilih</button>

                

            </div>
        </div>
    </div>
</div>
</form>
<br>

<?=form_open("$url"); ?>
<?php foreach ($header_supp as $a) { ?>
<hr>
<div class="col-sm-12">
    <div class="form-group row">
        <div class="col-sm-12">
            <h6><u>
                    <?=$a->namasupp; ?>
                </u></h6>
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="form-group row">

        <?php 
                $proses = $this->model_order->get_kodeprod($a->supp);
                foreach ($proses as $b) { ?>
        <div class="col-sm-4" id="test">
            <input type="checkbox" id="<?=$b->kodeprod; ?>" name="options[]"
                class="<?=$b->supp.$b->grup.$b->subgroup; ?>" value="<?=$b->kodeprod; ?>">
            <label for="<?=$b->kodeprod; ?>">
                <?= '('.$b->kodeprod.') '.$b->namaprod; ?>
            </label>
        </div>
        <?php
                }
            ?>
    </div>
</div>
<hr>

<?=form_submit('submit', 'Tambah ke keranjang', 'class="btn btn-primary" required'); ?>
<?=form_close(); ?>
<?php } ?>