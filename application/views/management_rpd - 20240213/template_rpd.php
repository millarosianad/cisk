<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>MPM Site</title>

    <style>
        .judul, .signature, th{
            font-size: 13px;
        }

        td{
            font-size: 12px;
        }


    </style>

</head>

<body>
    <table border="0" width="100%">
        <tr>
            <td width="15%">No</td>
            <td> : </td>
            <td width="84%"><?= $no_rpd; ?></td>
        </tr>
        <tr>
            <td width="15%">Pelaksana</td>
            <td> : </td>
            <td width="84%"><?= $pelaksana; ?></td>
        </tr>
        <tr>
            <td width="15%">Jabatan</td>
            <td> : </td>
            <td width="84%"><?= $jabatan; ?></td>
        </tr>
        <tr>
            <td width="15%">Tujuan</td>
            <td> : </td>
            <td width="84%"><?= $maksud_perjalanan_dinas; ?></td>
        </tr>
        <tr>
            <td width="15%">Periode</td>
            <td> : </td>
            <td width="84%"><?= $berangkat. ' s/d ' .$tiba; ?></td>
        </tr>
        <tr>
            <td width="15%">Periode Perdin</td>
            <td> : </td>
            <td width="84%"><?= $tanggal_mulai. ' s/d ' .$tanggal_akhir; ?></td>
        </tr>
        <tr>
            <td width="15%">Total Biaya</td>
            <td> : </td>
            <td width="84%">Rp. <?= number_format($total_biaya) ?></td>
        </tr>
    </table>
    
    <div class="text-center mt-5 mb-3 judul">
        Rencana Aktivitas Sebelum Perjalanan Dinas
    </div>

    <table class="table table-bordered">
        <tr>
            <th width="7%"><font size="12px">Tanggal</th>
            <th width="7%"><font size="12px">Aktivitas</th>
            <th width="7%"><font size="12px">Detail</th>
            <th width="7%"><font size="12px">Biaya</th>
            <th width="7%"><font size="12px">Status Claim</th>
            <th width="7%"><font size="12px">Keterangan</th>
        </tr>
        <?php foreach ($get_aktivitas->result() as $row) { ?>
        <tr>
            <td><font size="12px"><?php echo $row->tanggal_aktivitas; ?></td>
            <td><font size="12px"><?php echo $row->aktivitas; ?></td>
            <td><font size="12px"><?php echo $row->detail_aktivitas; ?></td>
            <td><font size="12px">Rp. <?php echo number_format($row->biaya); ?></td>
            <td><font size="12px">
                <?php 
                    if ($row->status_realisasi == 1) {
                        echo "Ya";
                    }else{
                        echo "Tidak";
                    }
                    // echo $row->status_realisasi; 
                ?>
            </td>
            <td><font size="12px"><?php echo $row->keterangan; ?></td>
        </tr>

        <?php } ?>
    </table>

    <div class="text-center mt-5 mb-3 judul">
        Realisasi Setelah Perjalanan Dinas
    </div>

    <?php 
        if ($cek_realisasi->num_rows() > 0) { ?>
            <div class="text-center"><i>Belum Ada Realisasi. Silahkan input data realisasi terlebih dahulu</i></div>
        <?php 
        }else{ ?>
            <table class="table table-bordered mt-3">
                <tr>
                    <th width="40%"><font size="12px">Rencana Aktivitas</th>
                    <th width="10%"><font size="12px">Apakah Tercapai</th>
                    <th width="30%"><font size="12px">Keterangan</th>
                </tr>
                <?php foreach ($get_aktivitas->result() as $row) { ?>
                <tr>
                    <td><font size="12px"><?php echo $row->aktivitas; ?></td>
                    <td><font size="12px">
                        <?php 
                            if ($row->status_realisasi == 1) {
                                echo "Ya";
                            }else{
                                echo "Tidak";
                            }
                            // echo $row->status_realisasi; 
                        ?>
                    </td>
                    <td><font size="12px"><?php echo $row->keterangan_realisasi; ?></td>
                </tr>

                <?php } ?>
            </table>

            <br>

            <!-- <?php 
                if ($jumlah_verifikasi == 2) { ?>

                    <table border="0" width="100%">
                        <tr>
                            <td align="center" width="25%">
                            <?php 
                                $file = './assets/uploads/signature/'.$verifikasi1_ttd;
                                if (file_exists($file)) { ?>
                                    <img src="<?= $file ?>" alt="<?= $verifikasi1_ttd ?>" width="150px">
                                <?php
                                } 
                            ?>   
                            </td>
                            <td align="center" width="25%">
                            <?php 
                                $file = './assets/uploads/signature/'.$verifikasi2_ttd;
                                if (file_exists($file)) { ?>
                                    <img src="<?= $file ?>" alt="<?= $verifikasi2_ttd ?>" width="150px">
                                <?php
                                } 
                            ?>  
                            </td>
                        </tr>
                        <tr>
                            <td align="center" width="25%" class="signature"><?= $username_verifikasi1 ?></td>
                            <td align="center" width="25%" class="signature"><?= $username_verifikasi2 ?></td>
                        </tr>

                        <tr>
                            <td align="center" class="signature"><?= $verifikasi1_at ?></td>
                            <td align="center" class="signature"><?= $verifikasi2_at ?></td>
                        </tr>
                    </table>

                <?php
                }else{ ?>
                    
                    <table border="0" width="100%">
                        <tr>
                            <td align="center" width="25%">
                            <?php 

                            if ($verifikasi1_ttd) {
                                $file = './assets/uploads/signature/iman-signature.png';
                                if (file_exists($file)) { ?>
                                    <img src="<?= $file ?>" alt="<?= $verifikasi1_ttd ?>" width="150px">
                                <?php
                                } 
                            }else{ ?>

                                <p><font color="red">Belum di Approve oleh Atasan</font></p>
                            <?php
                            }
                            
                            ?>   
                            </td>
                        </tr>
                        <tr>
                            <td align="center" width="25%" class="signature">( <?= $username_verifikasi1 ?> )</td>
                        </tr>

                        <tr>
                            <td align="center" class="signature"><?= $verifikasi1_at ?></td>
                        </tr>
                    </table>

                <?php
                }
            ?> -->

            


        <?php
        }
    ?>
    <?php 
                if ($jumlah_verifikasi == 2) { ?>

                    <table border="0" width="100%">
                        <tr>
                            <td align="center" width="25%">
                            <?php 
                                $file = './assets/uploads/signature/'.$verifikasi1_ttd;
                                if (file_exists($file)) { ?>
                                    <img src="<?= $file ?>" alt="<?= $verifikasi1_ttd ?>" width="150px">
                                <?php
                                } 
                            ?>   
                            </td>
                            <td align="center" width="25%">
                            <?php 
                                $file = './assets/uploads/signature/'.$verifikasi2_ttd;
                                if (file_exists($file)) { ?>
                                    <img src="<?= $file ?>" alt="<?= $verifikasi2_ttd ?>" width="150px">
                                <?php
                                } 
                            ?>  
                            </td>
                        </tr>
                        <tr>
                            <td align="center" width="25%" class="signature"><?= $username_verifikasi1 ?></td>
                            <td align="center" width="25%" class="signature"><?= $username_verifikasi2 ?></td>
                        </tr>

                        <tr>
                            <td align="center" class="signature"><?= $verifikasi1_at ?></td>
                            <td align="center" class="signature"><?= $verifikasi2_at ?></td>
                        </tr>
                    </table>

                <?php
                }else{ ?>
                    
                    <table border="0" width="100%">
                        <tr>
                            <td align="center" width="25%">
                            <?php 

                            if ($verifikasi1_ttd) {
                                $file = './assets/uploads/signature/iman-signature.png';
                                if (file_exists($file)) { ?>
                                    <img src="<?= $file ?>" alt="<?= $verifikasi1_ttd ?>" width="150px">
                                <?php
                                } 
                            }else{ ?>

                                <p><font color="red">Belum di Approve oleh Atasan</font></p>
                            <?php
                            }
                            
                            ?>   
                            </td>
                        </tr>
                        <tr>
                            <td align="center" width="25%" class="signature">( <?= $username_verifikasi1 ?> )</td>
                        </tr>

                        <tr>
                            <td align="center" class="signature"><?= $verifikasi1_at ?></td>
                        </tr>
                    </table>

                <?php
                }
            ?>

    

    


</body>

</html>