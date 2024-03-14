<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->
    <title>MPM Site</title>

    <style>
        td{
            font-size: 12px;
        }
        th{
            font-size: 11px;
        }
    </style>

</head>

<body>
    <table border="0" width="40%">
        <tr>
            <td>Principal</td>
            <td> : </td>
            <td><strong><?= $namasupp; ?></strong></td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td> : </td>
            <td><strong><?= $no_pengajuan; ?></strong></td>
        </tr>
        <tr>
            <td>Branch</td>
            <td> : </td>
            <td><strong><?= $branch_name.' - '.$nama_comp.' - '.$site_code; ?></strong></td>
        </tr>
    </table>
    <br>
    <?php
//   var_dump($detail);
  ?>
    <table class="table table-bordered">
        <tr>
            <th width="1%">#</th>
            <th width="7%">Kodeprod</th>
            <th width="10%">Namaprod</th>
            <th width="10%">Batch number</th>
            <th width="10%">Expired date</th>
            <th width="7%">Qty approval</th>
            <th width="7%">Satuan</th>
            <th width="10%">Nama outlet</th>
            <th width="10%">Keterangan</th>
            <th width="10%">Ket Principal Area</th>
        </tr>
        <?php 
      $no = 1;
      foreach ($get_pengajuan_detail->result() as $row) { ?>
        <tr>
            <td>
                <font size="12px"><?php echo $no++; ?>
            </td>
            <td>
                <font size="12px"><?php echo $row->kodeprod; ?>
            </td>
            <td>
                <font size="12px"><?php echo $row->namaprod; ?>
            </td>
            <td>
                <font size="12px"><?php echo $row->batch_number; ?>
            </td>
            <td>
                <font size="12px"><?php echo $row->expired_date; ?>
            </td>
            <td>
                <font size="12px"><?php echo $row->qty_approval; ?>
            </td>
            <td>
                <font size="12px"><?php echo $row->satuan; ?>
            </td>
            <td>
                <font size="12px"><?php echo $row->nama_outlet; ?>
            </td>
            <td>
                <font size="12px"><?php echo $row->keterangan; ?>
            </td>
            <td>
                <font size="12px"><?php echo $row->keterangan_principal_area; ?>
            </td>

        </tr>

        <?php } ?>
    </table>

    <br>

    <table border="0" width="100%">
        <tr>
            <td align="center" width="25%">DP</td>
            <td align="center" width="25%">Principal Area</td>
            <td align="center" width="25%">MPM</td>
            <td align="center" width="25%">Principal HO</td>
        </tr>


        <tr>
            <td align="center">
            <?php 
                $file = './assets/uploads/signature/'.$digital_signature;
                if (file_exists($file)) { ?>
                    <img src="<?= $file ?>" alt="<?= $digital_signature ?>" width="150px">
                <?php
                } 
            ?>   
            </td>
            <td align="center">
            <?php 
                $file = './assets/uploads/signature/'.$principal_area_signature;
                if ($principal_area_signature) { ?>
                    <img src="<?= $file ?>" alt="<?= $principal_area_signature ?>" width="150px">
                <?php
                }
            ?>   
            </td>
            <td align="center">
            <?php
                if ($verifikasi_signature) { ?>
                    <img src="./assets/css/images/ttd_p_fakhrul_stempel.jpg" alt="ttd" width="90px">
                <?php
                }

            ?>   
            </td>
            <td align="center"> 
            <?php 
                $file = './assets/uploads/signature/'.$principal_ho_signature;
                $file_edi_us = './assets/uploads/signature/edi-signature.png';
                if ($principal_ho_signature) { ?>

                    <?php 
                        if (file_exists($file_edi_us)) { ?>
                            <img src="<?= $file_edi_us ?>" alt="<?= $principal_ho_signature ?>" width="150px">
                        <?php
                        }else{ ?>
                            <img src="<?= $file ?>" alt="<?= $principal_ho_signature ?>" width="150px">
                        <?php
                        }
                    ?>

                    <!-- <img src="<?= $file ?>" alt="<?= $principal_ho_signature ?>" width="150px"> -->
                <?php
                }
            ?>   
            </td>
        </tr>

        <tr>
            <td align="center"><?= $nama ?></td>
            <td align="center"><?= $principal_area_username ?></td>
            <td align="center">Fakhrul Hidayat</td>
            <td align="center">
                <?php 
                        if (file_exists($file_edi_us)) { ?>                            
                            EDI
                        <?php
                        }else{ ?>
                            <?= $principal_ho_username ?>
                        <?php
                        }
                    ?>
                <!-- <?= $principal_ho_username ?> -->
            </td>
        </tr>
        <tr>
            <td align="center"><?= $created_at ?></td>
            <td align="center"><?= $principal_area_at ?></td>
            <td align="center"><?= $verifikasi_at ?></td>
            <td align="center"><?= $principal_ho_at ?></td>
        </tr>

    </table>

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->
</body>

</html>