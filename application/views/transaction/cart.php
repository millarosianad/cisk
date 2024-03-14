<!-- session flash data -->
<?php 
    if($this->session->flashdata('pesan')){ ?>
        <div class="alert alert-danger" role="alert">
            <?= $this->session->flashdata('pesan'); ?>
        </div>
    <?php
    }elseif($this->session->flashdata('pesan_success')){ ?>
        <div class="alert alert-success" role="alert">
            <?= $this->session->flashdata('pesan_success'); ?>
        </div>
    <?php
    }
?>

<a href="<?= base_url() . 'transaction/reset_cart/'.$this->uri->segment('3'); ?>" type="button"
    class="btn btn-dark btn-mat">Ulang dari awal</a>
<a href='<?= base_url() . "transaction/list_product?supp=$supp";?>' type="button" class="btn btn-success btn-mat">Tambah
    Produk</a>
<?= form_open($url); ?>
<div class="card-block">
    <div class="col-sm-12">
        <div class="form-group row">
            <div class="dt-responsive table-responsive">
                <table id="table-cart" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th>Kode Product</th>
                            <th>Product</th>
                            <th>QTY (Karton)</th>
                            <th>Berat (Kg)</th>
                            <th>Volume</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_data_cart as $a) : ?>
                        <tr>
                            <td>
                                <font size="2px"><?= $a->kodeprod; ?>
                            </td>
                            <td>
                                <font size="2px"><?= $a->namaprod; ?>
                            </td>
                            <td>
                                <input type="number" Style="text-align:right;" min="1" size="3" value="<?= $a->qty; ?>"
                                    class="form-control" id="amount" name="amount[<?= $a->id; ?>]" />
                            <td>
                                <font size="2px"><?= $a->berat; ?>
                            </td>
                            <td>
                                <font size="2px"><?= $a->volume; ?>
                            </td>
                            <td>
                                <center>
                                    <?php
                                        echo anchor('transaction/delete_cart/' . $this->uri->segment('3') . '/' . $a->id, '<i class="fa fa-times-circle-o fa-2x" style="color:red"></i>', array('onclick' => 'return confirm(\'Are you sure?\')'));
                                    ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-info" id="btnKirim" onclick="return button()">Lanjut ke Perhitungan Berat &
        Volume</button>
    <button class="btn btn-info" id="btnLoading" type="button" disabled>
        ... mohon tunggu, jangan tutup halaman ini ...
    </button>
    <?= form_close(); ?>
</div>

<script>
    $(document).ready(function () {
        $("#btnLoading").hide();
        $("#btnKirim").show();
    });

    function button() {
        $("#btnLoading").show();
        $("#btnKirim").hide();
    }
</script>