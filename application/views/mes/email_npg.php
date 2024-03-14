<!doctype html>
<html lang="en">
  <head>
  <style type="text/css">
    .button_accept {
        position: absolute;
        top:90%;
        padding-top: 5px;
        padding-bottom: 5px;
        padding-left: 8px;
        padding-right: 8px;
        background-color:green;
        color: #fff;
        border:none;
    }
    .button_reject {
        position: absolute;
        top:90%;
        padding-top: 5px;
        padding-bottom: 5px;
        padding-left: 8px;
        padding-right: 8px;
        background-color:orangered;
        color: #fff;
        border:none;
    }

    table
    {
        border-collapse: collapse;
    }

    th, td 
    {
        padding: 5px;
        text-align: left;
    }
  </style>

  </head>
  <body>

    <p>Bersama ini, kami kirimkan data penjualan E-commerce sebagai berikut untuk di fakturkan di SDS JKT</p>

    <table border="0">
        <tr>
            <td width="20%"> - Olshop</td>
            <td width="50%">: <?= $olshop; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Store</td>
            <td width="50%">: <?= $store; ?></td>
        </tr>
        <tr>
            <td width="20%"> - No Pesanan Gudang</td>
            <td width="50%">: <?= $no_pesanan_gudang; ?></td>
        </tr>
        <tr>
            <td width="20%">&nbsp;</td>
            <td width="50%">
            <a href="<?= base_url().'mes/piutang_konfirmasi/'.$signature ?>"><button type="button" class="button_accept">Klik disini untuk Input Nomor Faktur SDS</button></a>
            </td>
        </tr>
    </table>
    <br>
    <table border = 1>
        <tr>
            <th width="10%">Product ID</th>
            <th width="80%">Nama Product</th>
            <th width="10%">Qty</th>
        </tr>
        <?php foreach ($piutang_detail->result() as $a) : ?>
        <tr>
            <td><?= $a->productid ?></td>
            <td><?= $a->nama_product ?></td>
            <td><?= $a->qty ?></td>
        </tr>
        <?php endforeach; ?>

    </table>  

    

  </body>
</html>