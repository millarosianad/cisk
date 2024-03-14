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

    <p>Dear Principal Area Manager</p>
    <p>Berikut adalah Pengajuan Retur yang membutuhkan verifikasi anda</p>
        
    <table border="0">
        <tr>
            <td width="20%"> - No Retur</td>
            <td width="50%">: <?= $no_pengajuan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Principal</td>
            <td width="50%">: <?= $namasupp; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Branch | Site</td>
            <td width="50%">: <?= $branch_name. ' - ' .$nama_comp. ' ('.$site_code.')'; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Nama</td>
            <td width="50%">: <?= $nama; ?></td>
        </tr>
        <tr>
            <td width="20%"> - File</td>
            <td width="50%">: <?= $file; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Count Product</td>
            <td width="50%">: <?= number_format($count_kodeprod); ?></td>
        </tr>
        <tr>
            <td width="20%"> - Value Product</td>
            <td width="50%">: Rp. <?= number_format($value_rbp); ?></td>
        </tr>
    </table>
    <hr>
    <h4>Verifikasi MPM</h4>
    <table>
        <tr>
            <td width="20%"> - Verifikasi At</td>
            <td width="50%">: <?= $verifikasi_at; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Verifikasi By</td>
            <td width="50%">: <?= $verifikasi_username; ?></td>
        </tr>
        <tr>
            <td width="20%">&nbsp;</td>
            <td width="50%">

            <a href="<?= base_url().'management_inventory/routing/'.$signature.'/'.$supp ?>"><button type="button" class="button_accept">Verifikasi</button></a>

            </td>
        </tr>
    </table>
    <br>


    <table border = 1>
        <tr>
            <th width="10%">Kodeprod</th>
            <th width="10%">Namaprod</th>
            <th width="10%">Batch</th>
            <th width="10%">ED</th>
            <th width="10%">Jumlah</th>
            <th width="10%">Satuan</th>
            <th width="10%">Nama Outlet</th>
            <th width="10%">Ket</th>
        </tr>
        <?php foreach ($get_pengajuan_detail->result() as $a) : ?>
        <tr>
            <td><?= $a->kodeprod ?></td>
            <td><?= $a->namaprod ?></td>
            <td><?= $a->batch_number ?></td>
            <td><?= $a->expired_date ?></td>
            <td><?= $a->jumlah ?></td>
            <td><?= $a->satuan ?></td>
            <td><?= $a->nama_outlet ?></td>
            <td><?= $a->keterangan ?></td>
        </tr>
        <?php endforeach; ?>

    </table>  

    

  </body>
</html>