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
  Dear <strong><?= $company; ?></strong><br><br>
  Berikut data pesanan anda : <br><br>
  <table border = "0">
      <tr>
          <td width="1%">Company </td>
          <td> : </td>
          <td width="99%"><?= $company; ?></td>
      </tr>
      <br>
      <tr class="mt-3">
          <td width="1%">Alamat </td>
          <td> : </td>
          <td width="99%"><?= $alamat; ?></td>
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
      </tr>
      <?php 
      $no = 1;
      foreach ($detail as $row) { ?>
      <tr>
            <td><font size="12px"><?php echo $no++; ?></td>
            <td><font size="12px"><?php echo $row->kodeprod; ?></td>
            <td><font size="12px"><?php echo $row->namaprod; ?></td>
          
      </tr>

       <?php } ?>
  </table>

    

</body>
</html>