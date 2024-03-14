<!doctype html>
<html>

<head>
    <title><?= ucwords($header->kode); ?></title>
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
        <h1 style="font-size: 15h1x;">FORM PENGAJUAN PERJALANAN DINAS KELUAR KOTA</p>
    </center>
    <br><br>
    <table cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr>
                <td width="100px">Kode</td>
                <td width="2px">:</td>
                <td width="350px"><b><?= ucwords($header->kode); ?></b></td>
            </tr>
            <tr>
                <td width="100px">Nama</td>
                <td width="2px">:</td>
                <td width="350px"><?= ucwords($header->pelaksana); ?></td>
            </tr>
            <tr>
                <td width="50px">Cabang / Kota Tujuan</td>
                <td width="2px">:</td>
                <td><?= ucwords($header->tempat_tujuan); ?></td>
            </tr>
            <tr>
                <td width="50px">Waktu</td>
                <td width="2px">:</td>
                <td><?= date("d F Y", strtotime($header->tanggal_berangkat)) . ' s/d ' . date("d F Y", strtotime($header->tanggal_tiba)); ?></td>
            </tr>
            <tr>
                <td width="50px">Maksud / Tujuan Perjalanan</td>
                <td width="2px">:</td>
                <td><?= ucwords($header->maksud_perjalanan_dinas); ?></td>
            </tr>
        </tbody>
    </table>

    <br><br>
    <center>
    <h1 style="font-size: 15h1x;">Rencana Perjalanan Dinas</p>
    <table cellspacing="0" cellpadding="0" border="1" width="100%">
        <tbody>
            <tr>
                <th width="2px">No</th>
                <th width="100px">Tanggal</th>
                <th width="150px">Hal Yang Dilakukan (Aktivitas)</th>
                <th width="100px">PIC</th>
                <th width="75px">Kategori Pengeluaran</th>
                <th width="75px">Biaya</th>
                <th width="150px">Keterangan</th>
            </tr>
            <?php $i = '1';
            foreach ($aktivitas as $key => $value) : ?>
                <tr>
                    <td><?= $i++?></td>
                    <td><?= date("d F Y H:i:s", strtotime($value->tanggal));?></td>
                    <td><?= $value->rencana;?></td>
                    <td><?= $header->pelaksana;?></td>
                    <td><?= $value->nama_kategori;?></td>
                    <td>Rp. <?= number_format($value->nominal_biaya);?></td>
                    <td><?= $value->keterangan;?></td>
                </tr>
                <?php endforeach;?>
                <tr>
                    <td colspan="5">Total</td>
                    <td>Rp. <?= number_format($total_aktivitas);?></td>
                    <td></td>
                </tr>
        </tbody>
    </table>
    </center>

    <br><br>
    <center>
    <h1 style="font-size: 15h1x;">Realisasi Perjalanan Dinas</p>
    <table cellspacing="0" cellpadding="0" border="1" width="100%">
        <tbody>
            <tr>
                <th width="2px">No</th>
                <th width="100px">Tanggal</th>
                <th width="150px">Rencana (Aktivitas)</th>
                <th width="75px">Kategori Pengeluaran</th>
                <th width="75px">Biaya</th>
                <th width="150px">Keterangan</th>
            </tr>
            <?php $i = '1';
            foreach ($realisasi as $key) : ?>
                <tr>
                    <td><?= $i++?></td>
                    <td><?= date("d F Y H:i:s", strtotime($key->tanggal));?></td>
                    <td><?= $key->rencana;?></td>
                    <td><?= $key->jenis_pengeluaran;?></td>
                    <td>Rp. <?= number_format($key->nominal_biaya);?></td>
                    <td><?= $key->keterangan;?></td>
                </tr>
            <?php endforeach;?>
                <tr>
                    <td colspan="4">Total</td>
                    <td>Rp. <?= number_format($total_realisasi);?></td>
                    <td></td>
                </tr>
        </tbody>
    </table>
    </center>
</body>

</html>