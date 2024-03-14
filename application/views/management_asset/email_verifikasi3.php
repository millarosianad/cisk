<!doctype html>
<html lang="en">

<head>

    <style type="text/css">
        .button_accept {
            position: absolute;
            top: 90%;
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 8px;
            padding-right: 8px;
            background-color: green;
            color: #fff;
            border: none;
        }

        .button_reject {
            position: absolute;
            top: 90%;
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 8px;
            padding-right: 8px;
            background-color: orangered;
            color: #fff;
            border: none;
        }

        table {
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }
    </style>

</head>

<body>

    <p>Dear Tim IT</p>
    <p>Berikut adalah pengajuan asset yang membutuhkan verifikasi dari tim anda</p>

    <table border="0">
        <tr>
            <td width="20%"> - No PR</td>
            <td width="50%">: <?= $no_pr; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Divisi</td>
            <td width="50%">: <?= $divisi; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Nama Atasan</td>
            <td width="50%">: <?= $username_verifikasi1; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Nama yang mengajukan</td>
            <td width="50%">: <?= $username_pengajuan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Tanggal Pengajuan</td>
            <td width="50%">: <?= $tgl_pengajuan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Barang</td>
            <td width="50%">: <?= $barang; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Keterangan</td>
            <td width="50%">: <?= $keterangan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Total Biaya</td>
            <td width="50%">:Rp. <?= number_format($total_biaya); ?></td>
        </tr>
        <tr>
            <td width="20%">&nbsp;</td>
            <td width="50%">
                <a href="<?= base_url() ?>management_asset/pengajuan_asset_detail/<?= $signature_pengajuan; ?>/detail_finance"><button type="button"
                        class="button_accept">Approve / Reject</button></a>
            </td>
        </tr>
    </table>
    <br>
    <p style="text-align: center;">Rincian Asset</p>
    <table border = 1>
        <tr>
            <th width="10%">No</th>
            <th width="10%">Barang</th>
            <th width="50%">Spesifikasi</th>
            <th width="10%">Harga</th>
            <th width="10%">Link</th>
        </tr>
        <?php 
        $no = 1;
        foreach ($get_detail->result() as $a) : ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $a->barang; ?></td>
            <td><?= $a->spesifikasi; ?></td>
            <td>Rp. <?= number_format($a->harga); ?></td>
            <td><?= $a->link; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>  
</body>

</html>