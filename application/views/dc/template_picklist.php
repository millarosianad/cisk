<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <title>MPM Site</title>
</head>
<style>
    .tabel_header td {
        font-size: 13px;
    }
    .tabel_header th {
        width: 130px;
        font-size: 15px;
    }
    .tabel_header .pembatas{
        width: 5%;
    }
    .table td {
        font-size: 13px;
    }
    .table th {
        
        font-size: 15px;
    }
    .table .pembatas{
        width: 5%;
    }
</style>
<body>
<h4><center>Form PickList MPM</center> </h4>
  <table class="tabel_header">
    <tr>
        <th>Principal</th>
        <td class="pembatas"> : </td>
        <td><?= $namasupp; ?></td>
    </tr>
    <tr>
        <th>No. Surat Jalan</th>
        <td> : </td>
        <td><?= $nodo; ?></td>
    </tr>
    <tr>
        <th>Tanggal DO</th>
        <td> : </td>
        <td><?= $tgldo; ?></td>
    </tr>
    <tr>
        <th>Company</th>
        <td> : </td>
        <td><?= $company; ?></td>
    </tr>
    <tr>
        <th>Alamat Kirim</th>
        <td> : </td>
        <td><?= $alamat." (".$kode_alamat.")"; ?></td>
    </tr>
  </table>
  <table class = "table table-bordered mt-4">
    <tr>
        <th width="1%">No</th>
        <th width="10%">kodeprod</th>
        <th width="80%">namaprod</th>
        <th>Qty</th>
    </tr>
    <tbody>
    <?php $no = 1; ?>
        <?php 
        foreach ($detail_produk as $key) { ?>
    <tr>        
        <td><?= $no++ ;?></td>
        <td><?= $key->kodeprod;?></td>
        <td><?= $key->namaprod;?></td>
        <td><?= $key->masuk;?></td>
    </tr>
    <?php } 
        ?>
        </tbody>
        <!-- <tfoot>
        <tr>
            <td colspan="2" height="50px">disiapkan oleh</th>
            <td>dicek oleh</th>
            <td>diterima oleh</th>
        </tr>
        </tfoot> -->
        <tfoot>
        <tr>
            <th colspan="4" height="50px">disiapkan oleh (nama & ttd) :</th>
        </tr>
        <tr>
            <th colspan="4" height="50px">dicek oleh (nama & ttd) :</th>
        </tr>
        <tr>
            <th colspan="4" height="50px">diterima oleh (nama & ttd) :</th>
        </tr>
        </tfoot>
  </table>

  
</body>
</html>