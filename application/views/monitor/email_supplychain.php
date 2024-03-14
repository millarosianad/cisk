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

    <p>Deltomed Summary</p>
    <p>Automatic Email</p>
        
    <!-- <table border="0">
        <tr>
            <td width="20%"> - No Ajuan Relokasi</td>
            <td width="50%">: <?= $no_relokasi; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Principal</td>
            <td width="50%">: <?= $namasupp; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Tanggal Pengajuan</td>
            <td width="50%">: <?= $tanggal_pengajuan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - PIC</td>
            <td width="50%">: <?= $nama; ?></td>
        </tr>
        <tr>
            <td width="20%"> - From To</td>
            <td width="50%">: <?= $from_nama_comp.' -> '.$to_nama_comp; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Alasan</td>
            <td width="50%">: <?= $alasan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Status</td>
            <td width="50%">: <?= $nama_status; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Detail</td>
            <td width="50%">: <a href="http://site.muliaputramandiri.com/cisk/relokasi">silahkan kunjungi web kami</a></td>
        </tr>
        <tr>
            <td width="20%">&nbsp;</td>
            <td width="50%">

            <a href="<?= base_url() ?>master_data/accept_supplychain/<?= $signature ?>"><button type="button" class="button_accept">Accept Supplychain</button></a>
            <a href="<?= base_url() ?>master_data/reject_supplychain/<?= $signature ?>"><button type="button" class="button_reject">Reject Supplychain</button></a>

            </td>
        </tr>
    </table> -->
    <br>
    <table border = 1>
        <tr>
            <th width="10%">Kodeprod</th>
            <th width="80%">Namaprod</th>
            <th width="10%">Qty</th>
        </tr>
        <?php foreach ($history_produk->result() as $a) : ?>
        <tr>
            <td><?= $a->kodeprod ?></td>
            <td><?= $a->namaprod ?></td>
            <td><?= $a->qty ?></td>
        </tr>
        <?php endforeach; ?>

    </table>  

    

  </body>
</html>