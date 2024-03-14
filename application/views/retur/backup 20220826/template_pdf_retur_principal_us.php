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
          <th width="1%">#</th>
          <th width="7%"><font size="12px">kodeprod</th>
          <th width="10%"><font size="12px">namaprod</th>
          <th width="10%"><font size="12px">batch number</th>
          <th width="10%"><font size="12px">expired date</th>
          <th width="7%"><font size="12px">jumlah</th>
          <th width="7%"><font size="12px">satuan</th>
          <th width="10%"><font size="12px">nama_outlet</th>
          <th width="10%"><font size="12px">alasan</th>
          <th width="10%"><font size="12px">keterangan</th>
      </tr>
      <?php 
      $no = 1;
      foreach ($detail as $row) { ?>
      <tr>
            <td><font size="12px"><?php echo $no++; ?></td>
            <td><font size="12px"><?php echo $row->kodeprod; ?></td>
            <td><font size="12px"><?php echo $row->namaprod; ?></td>
            <td><font size="12px"><?php echo $row->batch_number; ?></td>
            <td><font size="12px"><?php echo $row->expired_date; ?></td>
            <td><font size="12px"><?php echo $row->jumlah; ?></td>
            <td><font size="12px"><?php echo $row->satuan; ?></td>
            <td><font size="12px"><?php echo $row->nama_outlet; ?></td>
            <td><font size="12px"><?php echo $row->alasan; ?></td>
            <td><font size="12px"><?php echo $row->keterangan; ?></td>
          
      </tr>

       <?php } ?>
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