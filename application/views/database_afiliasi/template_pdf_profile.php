<!doctype html>
<html>

<head>
    <title>MPM Site</title>
</head>
<style>
    table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid black;
        width: 50%;
        font-size: 75%;
        padding-left: 4px;
    }

    table th,
    table td {
        overflow: hidden !important;
        white-space: normal !important;
    }
</style>

<body>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="12">
                    <p align="center"> PROFILE DP AFILIASI PT MULIA PUTRA MANDIRI </p>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4">NAMA DP AFILIASI</td>
                <td colspan="8">
                    <?=strtoupper($profile->nama);?>
                </td>
            </tr>
            <tr>
                <td colspan="4">STATUS AFILIASI</td>
                <td align="center"><?php if($profile->status_afiliasi == 'head office'){?>
                    <img src="http://localhost:8080/cisk/assets/png/blue-check-mark.png" style='width: 20px'>
                    <?php }?>
                </td>
                <td>HEAD OFFICE</td>
                <td align="center"><?php if($profile->status_afiliasi == 'sub branch'){?>
                    <img src="http://localhost:8080/cisk/assets/png/blue-check-mark.png" style='width: 20px'>
                    <?php }?>
                </td>
                <td>SUB BRANCH</td>
                <td align="center"><?php if($profile->status_afiliasi == 'stock point'){?>
                    <img src="http://localhost:8080/cisk/assets/png/blue-check-mark.png" style='width: 20px'>
                    <?php }?>
                </td>
                <td>STOCK POINT</td>
                <td align="center"><?php if($profile->status_afiliasi == 'sales office'){?>
                    <img src="http://localhost:8080/cisk/assets/png/blue-check-mark.png" style='width: 20px'>
                    <?php }?>
                </td>
                <td>SALES OFFICE</td>
            </tr>
            <tr>
                <td rowspan="5" colspan="4">ALAMAT</td>
                <td colspan="2">NAMA JALAN</td>
                <td colspan="6"><?=strtoupper($profile->alamat);?></td>
            </tr>
            <tr>
                <td colspan="2">KOTA/KAB</td>
                <td colspan="6"><?=strtoupper($profile->kota);?></td>
            </tr>
            <tr>
                <td colspan="2">PROVINSI</td>
                <td colspan="6"><?=strtoupper($profile->propinsi);?></td>
            </tr>
            <tr>
                <td colspan="2">KECAMATAN</td>
                <td colspan="2"><?=strtoupper($profile->kecamatan);?></td>
                <td colspan="2">KELURAHAN</td>
                <td colspan="2"><?=strtoupper($profile->kelurahan);?></td>
            </tr>
            <tr>
                <td colspan="2">KODEPOS</td>
                <td colspan="2"><?= $profile->kodepos;?></td>
                <td colspan="2">NO. TELP</td>
                <td colspan="2"><?= $profile->telp;?></td>
            </tr>
            <tr>
                <td colspan="4">STATUS PROPERTY AFILIASI</td>
                <td colspan="2" align="center"><?php if($profile->status_properti == 'milik sendiri'){?>
                    <img src="http://localhost:8080/cisk/assets/png/blue-check-mark.png" style='width: 20px'>
                    <?php }?>
                </td>
                <td colspan="2">MILIK SENDIRI</td>
                <td colspan="2" align="center"><?php if($profile->status_properti == 'sewa'){?>
                    <img src="http://localhost:8080/cisk/assets/png/blue-check-mark.png" style='width: 20px'>
                    <?php }?>
                </td>
                <td colspan="2">SEWA</td>
            </tr>
            <tr>
                <td colspan="4">PRIODE SEWA</td>
                <td colspan="8"><?=$profile->sewa_from.' S/D '.$profile->sewa_to;?></td>
            </tr>
            <tr>
                <td colspan="4">HARGA SEWA/TH</td>
                <td colspan="8">Rp <?= number_format($profile->harga_sewa);?></td>
            </tr>
            <tr>
                <td colspan="4">BENTUK BANGUNAN</td>
                <td align="center"><?php if($profile->bentuk_bangunan == 'gudang'){?>
                    <img src="http://localhost:8080/cisk/assets/png/blue-check-mark.png" style='width: 20px'>
                    <?php }?>
                </td>
                <td>GUDANG</td>
                <td align="center"><?php if($profile->bentuk_bangunan == 'ruko'){?>
                    <img src="http://localhost:8080/cisk/assets/png/blue-check-mark.png" style='width: 20px'>
                    <?php }?>
                </td>
                <td>RUKO</td>
                <td align="center"><?php if($profile->bentuk_bangunan == 'rumah'){?>
                    <img src="http://localhost:8080/cisk/assets/png/blue-check-mark.png" style='width: 20px'>
                    <?php }?>
                </td>
                <td colspan="3">RUMAH</td>
            </tr>
            <tr>
                <td colspan="4">LUAS GUDANG</td>
                <td colspan="8"><?=$profile->luas_gudang;?> m2</td>
            </tr>
            <tr>
                <td colspan="4">LUAS KANTOR</td>
                <td colspan="8"><?=$profile->luas_kantor_total;?> m2</td>
            </tr>
            <tr>
                <td colspan="4">LUAS TOTAL BANGUNAN</td>
                <td colspan="8">
                    <?=
                    $profile->luas_gudang+$profile->luas_gudang_baik+$profile->luas_gudang_retur+
                    $profile->luas_gudang_karantina+$profile->luas_gudang_ac+$profile->luas_loading_dock+
                    $profile->luas_kantor_div_logistik+$profile->luas_kantor_total+$profile->luas_ruang_sales+
                    $profile->luas_ruang_finance+$profile->luas_ruang_logistik+$profile->luas_gudang_arsip;
                    ?> m2
                </td>
            </tr>
            <tr>
                <td colspan="4">LUAS TOTAL TANAH</td>
                <td colspan="8">
                    <?=
                    $profile->luas_gudang+$profile->luas_gudang_baik+$profile->luas_gudang_retur+
                    $profile->luas_gudang_karantina+$profile->luas_gudang_ac+$profile->luas_loading_dock+
                    $profile->luas_kantor_div_logistik+$profile->luas_kantor_total+$profile->luas_ruang_sales+
                    $profile->luas_ruang_finance+$profile->luas_ruang_logistik+$profile->luas_gudang_arsip;
                    ?> m2
                </td>
            </tr>
            <tr>
                <td colspan="4">JUMLAH PALLET</td>
                <td colspan="8">
                    <?=
                    $profile->pallet_gudang+$profile->pallet_gudang_baik+$profile->pallet_gudang_retur+
                    $profile->pallet_gudang_karantina+$profile->pallet_gudang_ac+$profile->pallet_gudang_loading
                    ?>
                </td>
            </tr>
            <tr>
                <td rowspan="5" colspan="4">OMZET AVG BULANAN</td>
                <td colspan="2">TOTAL</td>
                <td colspan="2">Rp.<?= number_format($omzet_total->omzet);?></td>
                <td colspan="2">CANDY</td>
                <td colspan="2">Rp.<?= number_format($omzet_candy->omzet);?></td>
            </tr>
            <tr>
                <td colspan="2">HERBAL</td>
                <td colspan="2">Rp.<?= number_format($omzet_herbal->omzet);?></td>
                <td colspan="2">ULTRA SAKTI</td>
                <td colspan="2">Rp.<?= number_format($omzet_us->omzet);?></td>
            </tr>
            <tr>
                <td colspan="2">INTRAFOOD</td>
                <td colspan="2">Rp.<?= number_format($omzet_intrafood->omzet);?></td>
                <td colspan="2">MARGUNA</td>
                <td colspan="2">Rp.<?= number_format($omzet_marguna->omzet);?></td>
            </tr>
            <tr>
                <td colspan="2">JAYA</td>
                <td colspan="2">Rp.<?= number_format($omzet_jaya->omzet);?></td>
                <td colspan="2">HNI</td>
                <td colspan="2">Rp.<?= number_format($omzet_hni->omzet);?></td>
            </tr>
            <tr>
                <td colspan="2">STRIVE</td>
                <td colspan="2">Rp.<?= number_format($omzet_strive->omzet);?></td>
                <td colspan="2">MDJ</td>
                <td colspan="2">Rp.<?= number_format($omzet_mdj->omzet);?></td>
            </tr>
            <tr>
                <td rowspan="3" colspan="4">JUML KARYAWAN</td>
                <td>TOTAL</td>
                <td><?=$profile->total_karyawan;?></td>
                <td>MANAGER</td>
                <td><?=$profile->total_manager;?></td>
                <td>SPV</td>
                <td><?=$profile->total_spv;?></td>
                <td>STAFF</td>
                <td><?=$profile->total_staff;?></td>
            </tr>
            <tr>
                <td colspan="1">PRIA</td>
                <td colspan="3"><?=$profile->total_pria;?></td>
                <td colspan="1">WANITA</td>
                <td colspan="3"><?=$profile->total_wanita;?></td>

            </tr>
            <tr>
                <td>SALES</td>
                <td><?=$profile->total_sales;?></td>
                <td>GUDANG</td>
                <td><?=$profile->total_gudang;?></td>
                <td>EKSPEDISI</td>
                <td><?=$profile->total_ekspedisi;?></td>
                <td>FINANCE</td>
                <td><?=$profile->total_finance;?></td>
            </tr>
            <tr>
                <td rowspan="2" colspan="4">JUML ALAT KERJA</td>
                <td>MG DELIVERY</td>
                <td></td>
                <td>MG SALES</td>
                <td></td>
                <td>HAND PALLET</td>
                <td><?=$profile->jumlah_hand_pallet;?></td>
                <td>KOMPUTER</td>
                <td></td>
            </tr>
            <tr>
                <td>TROLLEY</td>
                <td><?=$profile->jumlah_trolley;?></td>
                <td>PRINTER EPSON</td>
                <td colspan="1"></td>
                <td>PRINTER INK JET</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">JUML ARMADA DELIVERY</td>
                <td>MOTOR</td>
                <td><?=$profile->total_motor_pengiriman;?></td>
                <td>BLIND VAN</td>
                <td><?=$profile->total_blind_van;?></td>
                <td>TRUCK ENGKEL</td>
                <td><?=$profile->total_engkel;?></td>
                <td>TRUCK DOUBLE</td>
                <td><?=$profile->total_double;?></td>
            </tr>
            <!-- <tr>
                <td rowspan="3" colspan="4">FOTO LOKASI</td>
                <td colspan="4">FOTO NAMPAK DEPAN
                    <br><img src="http://localhost:8080/cisk/assets/file/database_afiliasi/<?=$profile->foto_tampak_depan;?>" alt="" style='width: 50px'>
                </td>
                <td colspan="4">FOTO GUDANG
                    <br><img src="http://localhost:8080/cisk/assets/file/database_afiliasi/<?=$profile->foto_gudang;?>" alt="" style='width: 50px'>
                </td>
            </tr>
            <tr>
                <td colspan="4">FOTO KANTOR
                <br><img src="http://localhost:8080/cisk/assets/file/database_afiliasi/<?=$profile->foto_kantor;?>" alt="" style='width: 50px'></td>
                <td colspan="4">FOTO AREA LOADING GUDANG
                <br><img src="http://localhost:8080/cisk/assets/file/database_afiliasi/<?=$profile->foto_area_loading_gudang;?>" alt="" style='width: 50px'></td>
            </tr>
            <tr>
                <td colspan="4">FOTO GUDANG BAIK
                <br><img src="http://localhost:8080/cisk/assets/file/database_afiliasi/<?=$profile->foto_gudang_baik;?>" alt="" style='width: 50px'></td>
                <td colspan="4">FOTO GUDANG RETUR
                <br><img src="http://localhost:8080/cisk/assets/file/database_afiliasi/<?=$profile->foto_gudang_retur;?>" alt="" style='width: 50px'></td>
            </tr> -->
        </tbody>
    </table>

</body>

</html>