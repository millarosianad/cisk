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

    <p>Dear Bapak/Ibu <?= $pelaksana?></p>
    <p>Akomodasi perjalanan dinas anda sudah diinput</p>
        
    <table border="0">
        <tr>
            <td width="20%"> - No RPD</td>
            <td width="50%">: <?= $no_rpd; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Pelaksana</td>
            <td width="50%">: <?= $pelaksana; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Jabatan</td>
            <td width="50%">: <?= $jabatan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Alasan</td>
            <td width="50%">: <?= $maksud_perjalanan_dinas; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Periode Perdin</td>
            <td width="50%">: <?= $tanggal_mulai. ' s/d ' .$tanggal_akhir; ?></td>
        </tr>
        <!-- <tr>
            <td width="20%"> - Berangkat</td>
            <td width="50%">: <?= $berangkat; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Tiba</td>
            <td width="50%">: <?= $tiba; ?></td>
        </tr>
        <tr> -->
            <td width="20%"> - Total Biaya</td>
            <td width="50%">:Rp. <?= number_format($total_biaya); ?></td>
        </tr>
        <tr>
            <td width="20%">&nbsp;</td>
            <td width="50%">
            <a href="<?= base_url() ?>management_rpd/input_akomodasi/<?= $signature_pengajuan ?>"><button type="button"
                        class="button_accept">Cek Akomodasi</button></a>
            </td>
        </tr>
    </table>
    <br>
    <table border = 1>
        <tr>
            <th width="10%">Tanggal</th>
            <th width="10%">Aktivitas</th>
            <th width="50%">Detail</th>
            <th width="10%">Biaya</th>
            <th width="10%">StatusClaim</th>
        </tr>
        <?php foreach ($get_aktivitas->result() as $a) : ?>
        <tr>
            <td><?= $a->tanggal_aktivitas ?></td>
            <td><?= $a->aktivitas ?></td>
            <td><?= $a->detail_aktivitas ?></td>
            <td>Rp. <?= number_format($a->biaya) ?></td>
            <td>
                <?= ($a->status_claim == 1) ? 'Ya' : 'No' ?>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>  

    

  </body>
</html>