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
        background-color:darkslategray;
        color: #fff;
        border:1px solid darkslategray ;
        border-radius: 5px;
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

    <p>Dear <?= $namasupp ?></p>
    <p>Berikut adalah update dari ajuan retur MPM</p>
    
    <h4>Data Pengajuan DP</h4>
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
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td width="20%"> - Verifikasi Principal Area at</td>
            <td width="50%">: <?= $principal_area_at; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Verifikasi Principal Area by</td>
            <td width="50%">: <?= $principal_area_username; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Verifikasi MPM at</td>
            <td width="50%">: <?= $verifikasi_at; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Verifikasi MPM by</td>
            <td width="50%">: <?= $verifikasi_username; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Verifikasi Principal HO at</td>
            <td width="50%">: <?= $principal_ho_at; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Verifikasi Principal HO by</td>
            <td width="50%">: <?= $principal_ho_username; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Catatan Principal HO</td>
            <td width="50%">: <?= $catatan_principal_ho; ?></td>
        </tr>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td width="20%">- Pengiriman Barang at</td>
            <td width="50%">: <?= $tanggal_kirim_barang; ?></td>
        </tr>
        <tr>
            <td width="20%">- Ekspedisi</td>
            <td width="50%">: <?= $nama_ekspedisi; ?></td>
        </tr>
        <tr>
            <td width="20%">- Estimasi Tiba at</td>
            <td width="50%">: <?= $est_tanggal_tiba; ?></td>
        </tr>
        <tr>
            <td width="20%">- Updated at</td>
            <td width="50%">: <?= $proses_kirim_barang_at; ?></td>
        </tr>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td width="20%"> - Tanggal Pemusnahan</td>
            <td width="50%">: <?= $tanggal_pemusnahan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - PIC Pemusnahan</td>
            <td width="50%">: <?= $nama_pemusnahan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - File Berita Acara Pemusnahan</td>
            <td width="50%">: <?= $file_pemusnahan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Foto Pemusnahan 1</td>
            <td width="50%">: <?= $foto_pemusnahan_1; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Foto Pemusnahan 2</td>
            <td width="50%">: <?= $foto_pemusnahan_2; ?></td>
        </tr>
        <tr>
            <td width="20%"><b> - Status</b></td>
            <td width="50%"><b>: <?= $nama_status; ?></b></td>
        </tr>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td width="50%">
                <a href="<?= base_url().'management_inventory/routing/'.$signature.'/'.$supp ?>"><button type="button" class="button_accept">Verifikasi</button></a>
            </td>
        </tr>
    </table>
    <br>

    <table border = 1>
        <tr>
            <th width="10%">Status</th>
            <th width="10%">Kodeprod</th>
            <th width="10%">Namaprod</th>
            <th width="10%">Batch</th>
            <th width="10%">ED</th>
            <th width="10%">QtyApproval</th>
            <th width="10%">Satuan</th>
            <th width="10%">Nama Outlet</th>
            <th width="10%">Ket</th>
        </tr>
        <?php foreach ($get_pengajuan_detail->result() as $a) : ?>
        <tr>
            <td><?= $a->nama_status ?></td>
            <td><?= $a->kodeprod ?></td>
            <td><?= $a->namaprod ?></td>
            <td><?= $a->batch_number ?></td>
            <td><?= $a->expired_date ?></td>
            <td><?= $a->qty_approval ?></td>
            <td><?= $a->satuan ?></td>
            <td><?= $a->nama_outlet ?></td>
            <td><?= $a->keterangan ?></td>
        </tr>
        <?php endforeach; ?>

    </table>    

    

  </body>
</html>