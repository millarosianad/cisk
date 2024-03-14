<!doctype html>
<html>
<?php
// var_dump($detail);die;?>

<head>
    <title>MPM Site</title>
</head>

<style>
    .img {
        position: absolute;
        top: 0px;
        left: 0px;
    }

    h1 {
        font-family: "Times New Roman", Times, serif;
        font-size: 30px;
    }

    .header {

        font-size: 12px;
        font-family: "Times New Roman", Times, serif;
        vertical-align: top;
        padding: 5px;
    }

    .product {
        font-size: 12px;
        font-family: "Times New Roman", Times, serif;
        vertical-align: top;
    }

    div.table {
        top: 300px;
        right: 0px;
        margin-bottom: 40px;
    }

    div.note {
        position: absolute;
        bottom: 0px;
        left: 0px;
        width: 55%;
        height: 15%;
    }

    div.ttd {
        position: absolute;
        bottom: 0px;
        right: 0px;
        width: 30%;
        height: 15%;
    }
</style>

<body>
    <img src="C:/xampp/htdocs/cisk/assets/css/images/mpm_new.jpg" class="img" alt="logo" width="70cm">
    <center>
        <h1>SURAT PESANAN BARANG</h1>
    </center>
    <br>
    <table class="header" cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr>
                <td class="header" width ="100px">Pemesan</td>
                <td class="header" width = "2px">:</td>
                <td class="header" width ="350px"><b>PT. MULIA PUTRA MANDIRI</b></td>
                <td class="header" width ="50px">Tgl. Dok</td>
                <td class="header" width = "2px">:</td>
                <td class="header"><?= $header->tglpo; ?></td>
            </tr>
            <tr>
                <td class="header">Di Kirim Kepada</td>
                <td class="header">:</td>
                <td class="header"><?= $header->company; ?></td>
                <td class="header">No. Dok</td>
                <td class="header">:</td>
                <td class="header"><?= $header->nopo; ?></td>
            </tr>
            <tr>
                <td class="header">NPWP</td>
                <td class="header">:</td>
                <td class="header"><?= $header->npwp; ?></td>
                <td class="header">Tipe. Dok</td>
                <td class="header">:</td>
                <td class="header"><?= $header->tipe; ?></td>
            </tr>
            <tr>
                <td class="header">Alamat</td>
                <td class="header">:</td>
                <td class="header"><?= $header->alamat; ?></td>
            </tr>
        </tbody>
    </table>

    <br>

    <div class="table product">
        <table style="max-width:100%;min-width:100%; text-align: center;" cellspacing="0" cellpadding="0" border="1">
            <thead>
                <tr>
                    <th>Kode Produk</th>
                    <th>Kode Prc</th>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Berat (Kg)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($detail as $key) { ?>
                <tr>
                    <td><?= $key->kodeprod; ?></td>
                    <td><?= $key->kode_prc; ?></td>
                    <td><?= $key->namaprod; ?></td>
                    <td><?= $key->banyak; ?></td>
                    <td><?= $key->sub_berat; ?></td>
                </tr>

                <?php }
                ?>

            </tbody>
        </table>
    </div>

    <div class="note">
        NOTE : <br>
        <?= $header->note; ?>
    </div>
    <div class="ttd" style="text-align: center;">
        Jakarta, <?= $header->tglpo; ?><br>
        Penanggung Jawab <br>
        <img src="C:/xampp/htdocs/cisk/assets/css/images/ttd.jpg" alt="ttd" width="90cm"><br>
        Herman Oscar
    </div>
</body>

</html>