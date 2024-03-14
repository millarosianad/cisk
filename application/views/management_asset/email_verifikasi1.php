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

    <p>Dear Bapak/Ibu <?= $username_verifikasi1 ?></p>
    <p>Berikut adalah pengajuan asset yang membutuhkan verifikasi anda</p>

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
            <td width="20%">&nbsp;</td>
            <td width="50%">
                <a href="<?= base_url() ?>management_asset/pengajuan_asset_detail/<?= $signature_pengajuan ?>/detail_atasan"><button type="button"
                        class="button_accept">Approve / Reject</button></a>
            </td>
        </tr>
    </table>
</body>

</html>