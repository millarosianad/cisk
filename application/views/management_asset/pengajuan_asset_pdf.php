<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
</style>

<body>
    <img src="C:/xampp/htdocs/cisk/assets/css/images/mpm_new.jpg" class="img" alt="logo" width="70cm">
    <center>
        <br>
        <h1 style="font-size: 20px;"><strong>PURCHASE REQUEST</strong></h1>
        <h1 style="font-size: 15px;">No : <?= ucwords($header[0]->no_pr); ?></h1>
    </center>
    <br>
    <hr>
    <table border="0">
        <tr>
            <td width="1%">Nama</td>
            <td> : </td>
            <td width="99%" style="text-transform: capitalize;"><strong><?= $header[0]->username; ?></strong></td>
        </tr>
        <tr>
            <td width="1%">Divisi</td>
            <td> : </td>
            <td width="99%" style="text-transform: capitalize;"><strong><?= $header[0]->divisi; ?></strong></td>
        </tr>
        <tr>
            <td width="1%">Barang</td>
            <td> : </td>
            <td width="99%" style="text-transform: capitalize;"><strong><?= $header[0]->barang; ?></strong></td>
        </tr>
        <tr>
            <td width="1%">Total Biaya</td>
            <td> : </td>
            <td width="99%" style="text-transform: capitalize;"><strong>Rp.
                    <?= number_format($header[0]->total_biaya); ?></strong></td>
        </tr>
        <tr>
            <td width="1%">Keterangan</td>
            <td> : </td>
            <td width="99%" style="text-transform: capitalize;"><strong><?= $header[0]->keterangan; ?></strong></td>
        </tr>
    </table>
    <br>
    <h5 style="text-align: center;">Rincian Barang</h5>
    <table class="table table-bordered">
        <tr>
            <th width="1%" style="background-color: darkslategray; text-align: center;">
                <font size="12px" style="color: white;">No
            </th>
            <th width="7%" style="background-color: darkslategray; text-align: center;">
                <font size="12px" style="color: white;">Barang
            </th>
            <th width="10%" style="background-color: darkslategray; text-align: center;">
                <font size="12px" style="color: white;">Spesifikasi
            </th>
            <th width="10%" style="background-color: darkslategray; text-align: center;">
                <font size="12px" style="color: white;">Harga
            </th>
        </tr>
        <?php 
            $no = 1;
            foreach ($detail as $row) { ?>
        <tr>
            <td>
                <font size="12px" style="text-transform: capitalize;"><?= $no++; ?>
            </td>
            <td>
                <font size="12px" style="text-transform: capitalize;"><?= $row->barang; ?>
            </td>
            <td>
                <font size="12px" style="text-transform: capitalize;"><?= $row->spesifikasi; ?>
            </td>
            <td>
                <font size="12px" style="text-transform: capitalize;">Rp. <?= number_format($row->harga); ?>
            </td>
        </tr>

        <?php } ?>
    </table>

    <br>

    <table border="0" width="100%">
        <tr>
            <td align="center" width="25%">Verifikasi By Atasan</td>
            <td align="center" width="25%">Verifikasi By IT</td>
            <td align="center" width="25%">Verifikasi By Finance</td>
        </tr>

        <tr>
            <td align="center">
                <?php 
                $file_atasan = './assets/uploads/signature/pengajuan_asset/'.$header[0]->ttd_atasan;
                if ($header[0]->ttd_atasan <> '' || $header[0]->ttd_atasan <> null) { ?>
                <img src="<?= $file_atasan ?>" alt="<?= $header[0]->ttd_atasan ?>" width="150px">
                <?php
                } 
            ?>
            <td align="center">
                <?php 
                $file_it = './assets/uploads/signature/pengajuan_asset/'.$header[0]->ttd_it;
                if ($header[0]->ttd_it <> '' || $header[0]->ttd_it <> null) { ?>
                <img src="<?= $file_it ?>" alt="<?= $header[0]->ttd_it ?>" width="150px">
                <?php
                } 
            ?>
            <td align="center">
                <?php 
                $file_finance = './assets/uploads/signature/pengajuan_asset/'.$header[0]->ttd_finance;
                if ($header[0]->ttd_finance <> '' || $header[0]->ttd_finance <> null) { ?>
                <img src="<?= $file_finance ?>" alt="<?= $header[0]->ttd_finance ?>" width="150px">
                <?php
                } 
            ?>
        </tr>

        <tr>
            <td align="center" style="text-transform: capitalize;"><?= $header[0]->username_atasan ?></td>
            <td align="center" style="text-transform: capitalize;"><?= $header[0]->username_it ?></td>
            <td align="center" style="text-transform: capitalize;"><?= $header[0]->username_finance ?></td>
        </tr>

        <tr>
            <td align="center"><?= $header[0]->tgl_konfirmasi_atasan ?></td>
            <td align="center"><?= $header[0]->tgl_konfirmasi_it ?></td>
            <td align="center"><?= $header[0]->tgl_konfirmasi_finance ?></td>
        </tr>

    </table>

</body>

</html>