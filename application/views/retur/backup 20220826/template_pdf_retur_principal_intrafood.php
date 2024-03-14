<?php 
    if ($get_discount) {
        foreach ($get_discount as $key) {
            $diskon = $key->diskon;
            $ppn = $key->ppn;
        }
        foreach ($get_total as $key) {
            $total = $key->total;
        }

        $value_ppn = round(($total - $diskon)*$ppn/100);
        $total_akhir = $total + $value_ppn;
        
    }else{
        foreach ($get_total as $key) {
            $total = $key->total;
        }
        $diskon = 0;
        $ppn = 10;
    
        $value_ppn = round(($total - $diskon)*$ppn/100);
        $total_akhir = $total + $value_ppn;

    }
    
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <title>MPM Site</title>
</head>
<body>
  Dear <strong><?= $namasupp; ?></strong><br><br>
  Kami ingin mengirimkan pengajuan retur kami dengan data sebagai berikut : <br><br>
  <table border = "0">
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
  </table>
  <br>
  <?php
//   var_dump($detail);
  ?>
  <table class = "table table-bordered">
    <tr>
        <th colspan="3"><center><font size="12px">Produk</th>
        <th colspan="3"><center><font size="12px">Total All</th>
        <th colspan="3"><center><font size="12px">Harga</th>
        <th colspan="3"><center><font size="12px"></th>
    </tr>
      <tr>
          <th width="1%">#</th>
          <th width="5%"><font size="12px">kodeprod</th>
          <th width="10%"><font size="12px">namaprod</th>
          <th width="5%"><font size="12px">karton</th>
          <th width="5%"><font size="12px">dus</th>
          <th width="5%"><font size="12px">pcs</th>
          <th width="5%"><font size="12px">karton</th>
          <th width="5%"><font size="12px">dus</th>
          <th width="5%"><font size="12px">pcs</th>
          <th width="5%"><font size="12px">value</th>
          <th width="5%"><font size="12px">kode_produksi</th>
          <th width="10%"><font size="12px">keterangan</th>
      </tr>
      <?php 
      $no = 1;
      foreach ($detail as $row) { ?>
      <tr>
            <td><font size="12px"><?php echo $no++; ?></td>
            <td><font size="12px"><?php echo $row->kodeprod; ?></td>
            <td><font size="12px"><?php echo $row->namaprod; ?></td>
            <td><font size="12px"><?php echo $row->total_karton; ?></td>
            <td><font size="12px"><?php echo $row->total_dus; ?></td>
            <td><font size="12px"><?php echo $row->total_pcs; ?></td>
            <td><font size="12px"><?php echo $row->harga_karton; ?></td>
            <td><font size="12px"><?php echo $row->harga_dus; ?></td>
            <td><font size="12px"><?php echo $row->harga_pcs; ?></td>
            <td><font size="12px"><?php echo $row->value; ?></td>
            <td><font size="12px"><?php echo $row->kode_produksi; ?></td>
            <td><font size="12px"><?php echo $row->keterangan; ?></td>
          
      </tr>

       <?php } ?>

       <tr>
          <th colspan="7" width="1%"></th>
          <th colspan="2"  width="5%"><font size="12px">Total</th>
          <th colspan="3" width="5%"><font size="12px"><?= $total ?></th>
       </tr>
       <tr>
          <th colspan="7" width="1%"></th>
          <th colspan="2"  width="5%"><font size="12px">Discount</th>
          <th colspan="3" width="5%"><font size="12px"><?= $diskon ?></th>
       </tr>
       <tr>
          <th colspan="7" width="1%"></th>
          <th width="5%"><font size="12px">PPN</th>
          <th width="5%"><font size="12px"><?= $ppn ?> %</th>
          <th colspan="3" width="5%"><font size="12px"><?= $value_ppn ?></th>
       </tr>
       <tr>
          <th colspan="7" width="1%"></th>
          <th colspan="2"  width="5%"><font size="12px">Total Akhir</th>
          <th colspan="3" width="5%"><font size="12px"><?= $total_akhir ?></th>
       </tr>

  </table>

    <br>

    <table border="0" width="100%">
        <tr>
            <td>Di ajukan oleh,</td>
            <td align="right">Di setujui oleh,</td>
        </tr>


        <tr>
            <td><img src="assets_new/images/verified.png" alt="mulia putra mandiri" style="position: absolute; width: 80px; height: auto;"></td>
            <td align="right"></td>
        </tr>


        <tr>
            <td><br><br><br>Distributor</td>
            <td align="right"><br><br><br>Principal</td>
        </tr>

    </table>

</body>
</html>