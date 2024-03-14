<!doctype html>
<html>

<head>
    <title><?= ucwords($header->username).' - Periode '.date('d F Y', strtotime($from)).' S/D '. date('d F Y', strtotime($to)); ?></title>
</head>
<style>
    img {
        position: absolute;
        top: 0px;
        left: 0px;
    }

    h1 {
        font-family: "Times New Roman", Times, serif;
    }

    th{
        text-align: center;
    }

    th,
    td {
        padding: 4px;
        font-size: 12px;
    }

    /*
    table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid black;
        width: 50%;
        font-size: 10px;
        padding: 4px;
    }

    table th,
    table td {
        overflow: hidden !important;
        white-space: normal !important;
    } */
</style>

<body>
    <img src="C:/xampp/htdocs/cisk/assets/css/images/mpm_new.jpg" class="img" alt="logo" width="70cm">
    <center>
        <h1 style="font-size: 20px;">PT MULIA PUTRA MANDIRI</h1>
        <h1 style="font-size: 15h1x;">FORM REIMBURSE BIAYA OPERASIONAL</p>
    </center>
    <br><br>
    <table cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr>
                <td width="100px">Nama</td>
                <td width="2px">:</td>
                <td width="350px"><?= ucwords($username); ?></td>
            </tr>
            <tr>
                <td width="50px">Periode</td>
                <td width="2px">:</td>
                <td><?= date('d F Y', strtotime($from)).' s/d '. date('d F Y', strtotime($to)) ; ?></td>
            </tr>
            <tr>
                <td width="50px">Kategori</td>
                <td width="2px">:</td>
                <td>Bensin</td>
            </tr>
        </tbody>
    </table>

    <br>
    <center>
    <h1 style="font-size: 15h1x;">Detail Biaya Operasional</p>
    <table cellspacing="0" cellpadding="0" border="1" width="100%">
        <tbody>
            <tr>
                <th width="2px">No</th>
                <th width="100px">Tanggal</th>
                <th width="50px">Kilometer Awal</th>
                <th width="50px">Kilometer Akhir</th>
                <th width="50px">Total Kilometer</th>
                <th width="50px">Liter Bensin</th>
                <th width="90px">Biaya</th>
                <th width="50px">Konsumsi Bensin</th>
                <th width="10px">Reimburse</th>
            </tr>
            <?php $i = '1';
            foreach ($detail as $key) : ?>
                <tr>
                    <td><?= $i++?></td>
                    <td><?= date("d F Y", strtotime($key->tanggal_transaksi));?></td>
                    <td>
                        <?php if ($key->km_awal == '') {
                            echo '0';
                        } else {
                            echo number_format($key->km_awal);
                        }
                        ?></td>
                    <td><?= number_format($key->km_akhir);?></td>
                    <td><?= number_format($key->km_akhir-$key->km_awal);?></td>
                    <td><?= $key->liter;?></td>
                    <td>Rp. <?= number_format($key->biaya);?></td>
                    <td><?= round(($key->km_akhir - $key->km_awal)/$key->liter,2);?></td>
                    <td><?php if ($key->reimburse == '1' ) {
                        echo 'Ya';
                    } else {
                        echo 'Tidak';
                    }
                    ?></td>
                <?php endforeach;?>
                <tr>
                    <td colspan="6" style="text-align: center;">Total Reimburse</td>
                    <td>Rp. <?php if ($total_reimburse->total_biaya == '') {
                        echo '0';
                    } else {
                        echo number_format($total_reimburse->total_biaya);
                    }
                    ?></td>
                    <td colspan="2"></td>
                </tr>
        </tbody>
    </table>
    </center>
</body>

</html>