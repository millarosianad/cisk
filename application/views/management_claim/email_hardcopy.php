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

    <br>

    <table border="0">
        
        <!-- <tr>
            <td colspan="2"><hr></td>
        </tr> -->
        <tr>
            <td width="20%"><b> - Status</b></td>
            <td width="50%"><b>: <?= $nama_status; ?></b></td>
        </tr>
        <tr>
            <td width="20%"><b> - No Pengajuan</b></td>
            <td width="50%"><b>: <?= $nomor_ajuan; ?></b></td>
        </tr>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td width="20%"> - Nomor surat</td>
            <td width="50%">: <?= $nomor_surat; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Nama program</td>
            <td width="50%">: <?= $nama_program; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Kategori</td>
            <td width="50%">: <?= $kategori; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Periode</td>
            <td width="50%">: <?= $periode; ?></td>
        </tr>
        <tr>
            <td width="20%"> - File PDF</td>
            <td width="50%">
                <?php 
                    if ($upload_pdf) { ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/'.$upload_pdf ?>"><button type="button" class="button_accept"><?= $upload_pdf ?></button></a>
                    <?php
                    }else{
                        echo ": tidak ada file yang dilampirkan";
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td width="20%"> - File JPG</td>
            <td width="50%">
                <?php 
                    if ($upload_jpg) { ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/'.$upload_jpg ?>"><button type="button" class="button_accept"><?= $upload_jpg ?></button></a>
                    <?php
                    }else{
                        echo ": tidak ada file yang dilampirkan";
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td width="20%"> - Branch</td>
            <td width="50%">: <?= $branch_name.' | '.$nama_comp; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Nama Pengirim</td>
            <td width="50%">: <?= $nama_pengirim; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Email Pengirim</td>
            <td width="50%">: <?= $email_pengirim; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Ajuan Excel</td>
            <td width="50%">
                <?php 
                    if ($kategori == 'bonus_barang' || $kategori == 'diskon_herbal' || $kategori == 'diskon_candy' || $kategori == 'diskon') {
                        $params_folder = "import/";
                    }else{
                        $params_folder = "";
                    }
                    if ($ajuan_excel) { ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/'.$params_folder.$ajuan_excel ?>"><button type="button" class="button_accept"><?= $ajuan_excel ?></button></a>
                    <?php
                    }else{
                        echo ": tidak ada file yang dilampirkan";
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td width="20%"> - Ajuan Zip</td>
            <td width="50%">
                <?php 
                    if ($ajuan_zip) { ?>
                        <a href="<?= base_url().'assets/uploads/management_claim/'.$ajuan_zip ?>"><button type="button" class="button_accept"><?= $ajuan_zip ?></button></a>
                    <?php
                    }else{
                        echo ": tidak ada file yang dilampirkan";
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td width="20%"> - Tanggal Claim at</td>
            <td width="50%">: <?= $tanggal_claim; ?></b></td>
        </tr>
        <tr>
            <td width="20%"> - Created at</td>
            <td width="50%">: <?= $created_at; ?></b></td>
        </tr>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td width="20%"> - Keterangan Verifikasi MPM</td>
            <td width="50%">: <?= $verifikasi_keterangan; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Verifikasi MPM at</td>
            <td width="50%">: <?= $verifikasi_created_at; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Verifikasi MPM by</td>
            <td width="50%">: <?= $verifikasi_username; ?></td>
        </tr>
        
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td width="20%"> - Nama Status Hardcopy</td>
            <td width="50%">: <?= $nama_status_hardcopy; ?></td>
        </tr>
        <tr>
            <td width="20%"> - File Hardcopy</td>
            <td width="50%">: <?= $file_hardcopy; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Nomor Resi</td>
            <td width="50%">: <?= $nomor_hardcopy; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Tanggal Kirim Hardcopy</td>
            <td width="50%">: <?= $tanggal_kirim_hardcopy; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Nama Pengirim Hardcopy</td>
            <td width="50%">: <?= $nama_pengirim_hardcopy; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Email Pengirim Hardcopy</td>
            <td width="50%">: <?= $email_pengirim_hardcopy; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Last Update Pengiriman Hardcopy</td>
            <td width="50%">: <?= $update_kirim_hardcopy_at; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Tanggal Terima Hardcopy</td>
            <td width="50%">: <?= $tanggal_terima_hardcopy; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Last Update Terima Hardcopy</td>
            <td width="50%">: <?= $update_terima_hardcopy_at; ?></td>
        </tr>
        <tr>
            <td width="20%"> - File Tanda Terima Hardcopy ke Principal</td>
            <td width="50%">: <?= $file_tanda_terima_hardcopy_ke_principal; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Penerima Hardcopy oleh Principal</td>
            <td width="50%">: <?= $tanda_terima_hardcopy_ke_principal_nama; ?></td>
        </tr>
        <tr>
            <td width="20%"> - Tanggal Terima Hardcopy oleh Principal</td>
            <td width="50%">: <?= $tanggal_tanda_terima_hardcopy_ke_principal; ?></td>
        </tr>
        <tr>
            <td width="20%"></td>
            <td width="50%">
                <a href="<?= base_url().'management_claim/routing/'.$signature_program.'/'.$signature ?>"><button type="button" class="button_accept">View Detail</button></a>
            </td>
        </tr>
    </table>
    <br>

    

  </body>
</html>