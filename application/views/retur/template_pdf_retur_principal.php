<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>MPM Site</title>
    <style>
    td {
        text-align: left;
        font-size: 12px;
    }
    th {
        text-align: left;
        font-size: 13px;
    }
    </style>
</head>

<body>
    Dear <strong><?= $namasupp; ?></strong><br><br>
    Kami ingin mengirimkan pengajuan retur kami dengan data sebagai berikut : <br><br>
    <table border="0">
        <tr>
            <td width="1%">No Ajuan Retur </td>
            <td> : </td>
            <td width="99%"><strong><?= $no_pengajuan; ?></strong></td>
        </tr>
        <tr>
            <td width="1%">Company </td>
            <td> : </td>
            <td width="99%"><?= $company; ?></td>
        </tr>
        <tr>
            <td>Branch </td>
            <td> : </td>
            <td><?= $branch_name; ?> - <?= $nama_comp; ?></td>
        </tr>
        <tr>
            <td>Note </td>
            <td> : </td>
            <td><?= $note?></td>
        </tr>
    </table>
    <br>
    <?php
//   var_dump($detail);
?>
    <table class="table table-bordered">
        <tr>
            <th width="1%">#</th>
            <th width="10%">kodeprod</th>
            <th width="20%">namaprod</th>
            <th width="20%">batch number</th>
            <th width="20%">expired date</th>
            <th width="9%">jumlah</th>
            <th width="20%">alasan</th>
            <th width="20%">keterangan</th>
        </tr>
        <?php 
      $no = 1;
      foreach ($detail as $row) { ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $row->kodeprod; ?></td>
            <td><?php echo $row->namaprod; ?></td>
            <td><?php echo $row->batch_number; ?></td>
            <td><?php echo $row->expired_date; ?></td>
            <td><?php echo $row->jumlah; ?></td>
            <td><?php echo $row->alasan; ?></td>
            <td><?php echo $row->keterangan; ?></td>
        </tr>

        <?php } ?>
    </table>

    <br>

    <table border="0" width="100%">
        <tr>
            <td width="40%">(*) Alasan Retur</td>
            <td><center>Di ajukan oleh,</td>
            <td><center>Di setujui oleh,</td>
        </tr>
        <tr>
            <td><font size="13px">
                A Cacat Produksi (sesuai kriteria)<br>
                B Kadaluarsa (sesuai ketentuan)<br>
                C Produk Discontinue<br>
                D Penarikan Pabrik<br>
                E Salah Kirim oleh Principal<br>
                F Rusak dalam Pengiriman (ada bukti kerusakan terjadi dalam pengiriman)<br>
                G Lain-lain
                </font>
            </td>
            <td><center>
                <img src="assets_new/images/verified.png" alt="mulia putra mandiri" style="width: 80px; height: auto;">
                </center>
            </td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td><center>Distributor</td>
            <td><center>Principal</td>
        </tr>
    </table>
</body>
</html>