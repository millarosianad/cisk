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
        font-size: 10px;
        padding: 4px;
    }

    table th,
    table td {
        overflow: hidden !important;
        white-space: normal !important;
    }
</style>

<body>
    <div class="row">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="12">
                    <p align="center">DATA DETAIL GUDANG DAN KANTOR</p>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4">LUAS GUDANG - in Meter2</td>
                <td>TOTAL</td>
                <td colspan="2"><?= $luas_gudang;?> m2</td>
                <td>PANJANG</td>
                <td colspan="2"><?= $panjang_gudang;?> m</td>
                <td>LEBAR</td>
                <td><?= $lebar_gudang;?> m</td>
            </tr>
            <tr>
                <td colspan="4">LUAS GUDANG - in Pallet</td>
                <td>TOTAL PALLET</td>
                <td colspan="2"><?= $pallet_gudang;?></td>
                <td>RACKING</td>
                <td colspan="4"><?= $racking_gudang;?> m2</td>
            </tr>
            <tr>
                <td colspan="4">LUAS GUDANG BAIK - in Meter2</td>
                <td>TOTAL</td>
                <td colspan="2"><?= $luas_gudang_baik;?> m2</td>
                <td>PANJANG</td>
                <td colspan="2"><?= $panjang_gudang_baik;?> m</td>
                <td>LEBAR</td>
                <td><?= $lebar_gudang_baik;?> m</td>
            </tr>
            <tr>
                <td colspan="4">LUAS GUDANG BAIK - in Pallet</td>
                <td>TOTAL PALLET</td>
                <td colspan="2"><?= $pallet_gudang_baik;?></td>
                <td>RACKING</td>
                <td colspan="4"><?= $racking_gudang_baik;?> m2</td>
            </tr>
            <tr>
                <td colspan="4">LUAS GUDANG RETUR - in Meter2</td>
                <td>TOTAL</td>
                <td colspan="2"><?= $luas_gudang_retur;?> m2</td>
                <td>PANJANG</td>
                <td colspan="2"><?= $panjang_gudang_retur;?> m</td>
                <td>LEBAR</td>
                <td><?= $lebar_gudang_retur;?> m</td>
            </tr>
            <tr>
                <td colspan="4">LUAS GUDANG RETUR - in Pallet</td>
                <td>TOTAL PALLET</td>
                <td colspan="2"><?= $pallet_gudang_retur;?></td>
                <td>RACKING</td>
                <td colspan="4"><?= $racking_gudang_retur;?> m2</td>
            </tr>
            <tr>
                <td colspan="4">LUAS GUDANG KARANTINA - in Meter2</td>
                <td>TOTAL</td>
                <td colspan="2"><?= $luas_gudang_karantina;?> m2</td>
                <td>PANJANG</td>
                <td colspan="2"><?= $panjang_gudang_karantina;?> m</td>
                <td>LEBAR</td>
                <td><?= $lebar_gudang_karantina;?> m</td>
            </tr>
            <tr>
                <td colspan="4">LUAS GUDANG KARANTINA - in Pallet</td>
                <td>TOTAL PALLET</td>
                <td colspan="2"><?= $pallet_gudang_karantina;?></td>
                <td>RACKING</td>
                <td colspan="4"><?= $racking_gudang_karantina;?> m2</td>
            </tr>
            <tr>
                <td colspan="4">LUAS GUDANG AC (JIKA ADA) - in Meter2</td>
                <td>TOTAL</td>
                <td colspan="2"><?= $luas_gudang_ac;?> m2</td>
                <td>PANJANG</td>
                <td colspan="2"><?= $panjang_gudang_ac;?> m</td>
                <td>LEBAR</td>
                <td><?= $lebar_gudang_ac;?> m</td>
            </tr>
            <tr>
                <td colspan="4">LUAS GUDANG AC (JIKA ADA) - in Pallet</td>
                <td>TOTAL PALLET</td>
                <td colspan="2"><?= $pallet_gudang_karantina;?></td>
                <td>RACKING</td>
                <td colspan="4"><?= $racking_gudang_karantina;?> m2</td>
            </tr>
            <tr>
                <td colspan="4">LUAS LOADING DOCK - in Meter2</td>
                <td>TOTAL</td>
                <td colspan="2"><?= $luas_loading_dock;?> m2</td>
                <td>PANJANG</td>
                <td colspan="2"><?= $panjang_loading_dock;?> m</td>
                <td>LEBAR</td>
                <td><?= $lebar_loading_dock;?> m</td>
            </tr>
            <tr>
                <td colspan="4">LUAS LOADING DOCK - in Pallet</td>
                <td>TOTAL PALLET</td>
                <td colspan="2"><?= $pallet_gudang_loading;?></td>
                <td>RACKING</td>
                <td colspan="4"><?= $racking_gudang_loading;?> m2</td>
            </tr>
            <tr>
                <td colspan="4">JUMLAH ALAT KERJA</td>
                <td><?= $jumlah_pallet;?></td>
                <td>PALLET</td>
                <td><?= $jumlah_hand_pallet;?></td>
                <td>HAND PALLET</td>
                <td><?= $jumlah_trolley;?></td>
                <td>TROLLEY</td>
                <td><?= $jumlah_sealer;?></td>
                <td>SEALER</td>
            </tr>
            <tr>
                <td colspan="4">SIRKULASI UDARA GUDANG BAIK</td>
                <td><?= $jumlah_ac;?></td>
                <td>AC</td>
                <td><?= $jumlah_exhaust_fan;?></td>
                <td>EXHAUST FAN</td>
                <td><?= $jumlah_kipas_angin;?></td>
                <td colspan="3">KIPAS ANGIN</td>
            </tr>
            <tr>
                <td colspan="4">JUMLAH KARYAWAN - SECARA UMUM</td>
                <td><?= $total_pria + $total_wanita;?></td>
                <td>TOTAL</td>
                <td><?= $total_pria;?></td>
                <td>LAKI LAKI</td>
                <td><?= $total_wanita;?></td>
                <td colspan="3">WANITA</td>
            </tr>
            <tr>
                <td colspan="4">JUMLAH KARYAWAN - By Divisi</td>
                <td><?= $total_karyawan;?></td>
                <td>TOTAL</td>
                <td><?= $total_gudang;?></td>
                <td>GUDANG</td>
                <td><?= $total_ekspedisi;?></td>
                <td colspan="3">EKSPEDISI</td>
            </tr>
            <tr>
                <td colspan="4">JUMLAH KARYAWAN - By Level</td>
                <td><?= $total_spv;?></td>
                <td>SPV</td>
                <td><?= $total_staff;?></td>
                <td>STAFF</td>
                <td></td>
                <td colspan="3">PELAKSANA</td>
            </tr>
            <tr>
                <td colspan="4">JUMLAH KARYAWAN - By Jabatan</td>
                <td><?= $total_kalog;?></td>
                <td>KALOG</td>
                <td><?= $total_admin;?></td>
                <td>ADM</td>
                <td><?= $total_picker;?></td>
                <td>PICKER / CHECKER</td>
                <td><?= $total_picker;?></td>
                <td>DRIVER / LOOPER</td>
            </tr>
            <tr>
                <td colspan="4">LUAS KANTOR DIV LOGISTIK</td>
                <td>TOTAL</td>
                <td colspan="2"><?= $luas_kantor_div_logistik;?> m2</td>
                <td>PANJANG</td>
                <td colspan="2"><?= $panjang_kantor_div_logistik;?> m</td>
                <td>LEBAR</td>
                <td><?= $lebar_kantor_div_logistik;?> m</td>
            </tr>
            <tr>
                <td colspan="4" rowspan="6">JUMLAH ARMADA LOGISTIK</td>
                <td><?= $total_mobil_penumpang;?></td>
                <td>TOTAL MOBIL PENUMPANG</td>
                <td><?= $jumlah_mobil_penumpang_sewa;?></td>
                <td>SEWA</td>
                <td><?= $jumlah_mobil_penumpang_milik_sendiri;?></td>
                <td colspan="3">MILIK SENDIRI</td>
            </tr>
            <tr>
                <td><?= $total_mobil_pengiriman;?></td>
                <td>TOTAL MOBIL PENGIRIMAN</td>
                <td><?= $jumlah_mobil_pengiriman_blind_van;?></td>
                <td>BLIND VAN</td>
                <td><?= $jumlah_mobil_pengiriman_engkel;?></td>
                <td>ENGKEL</td>
                <td><?= $jumlah_mobil_pengiriman_double;?></td>
                <td>DOUBLE</td>
            </tr>
            <tr>
                <td><?= $total_blind_van;?></td>
                <td>TOTAL BLIND VAN</td>
                <td><?= $jumlah_blind_van_sewa;?></td>
                <td>SEWA</td>
                <td><?= $jumlah_blind_van_milik_sendiri;?></td>
                <td colspan="3">MILIK SENDIRI</td>
            </tr>
            <tr>
                <td><?= $total_engkel;?></td>
                <td>TOTAL ENGKEL</td>
                <td><?= $jumlah_engkel_sewa;?></td>
                <td>SEWA</td>
                <td><?= $jumlah_engkel_milik_sendiri;?></td>
                <td colspan="3">MILIK SENDIRI</td>
            </tr>
            <tr>
                <td><?= $total_double;?></td>
                <td>TOTAL DOUBLE</td>
                <td><?= $jumlah_double_sewa;?></td>
                <td>SEWA</td>
                <td><?= $jumlah_double_milik_sendiri;?></td>
                <td colspan="3">MILIK SENDIRI</td>
            </tr>
            <tr>
                <td><?= $total_motor_pengiriman;?></td>
                <td>TOTAL MOTOR PENGIRIMAN</td>
                <td><?= $jumlah_motor_pengiriman_sewa;?></td>
                <td>SEWA</td>
                <td><?= $jumlah_motor_pengiriman_milik_sendiri;?></td>
                <td colspan="3">MILIK SENDIRI</td>
            </tr>
            <tr>
                <td colspan="4">JUMLAH SADDLE BAG</td>
                <td><?= $total_saddle_bag;?></td>
                <td>TOTAL SADDLE BAG</td>
                <td><?= $jumlah_saddle_bag_dipakai;?></td>
                <td>SADDLE BAG DIPAKAI</td>
                <td><?= $jumlah_saddle_bag_cadangan;?></td>
                <td colspan="3">SADDLE BAG CADANGAN</td>
            </tr>
        </tbody>
    </table>
    </div>
    <br><br>
    <br><br>
    <br>
    <div class="row">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="12">
                    <p align="center">DATA DETAIL RUANGAN KANTOR</p>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4">LUAS KANTOR TOTAL  - in Meter2</td>
                <td>TOTAL</td>
                <td colspan="2"><?= $luas_kantor_total;?> m2</td>
                <td>PANJANG</td>
                <td colspan="2"><?= $panjang_kantor_total;?> m</td>
                <td>LEBAR</td>
                <td><?= $lebar_kantor_total;?> m</td>
            </tr>
            <tr>
                <td colspan="4">LUAS RUANG SALES - in Meter2</td>
                <td>TOTAL</td>
                <td colspan="2"><?= $luas_ruang_sales;?> m2</td>
                <td>PANJANG</td>
                <td colspan="2"><?= $panjang_ruang_sales;?> m</td>
                <td>LEBAR</td>
                <td><?= $lebar_ruang_sales;?> m</td>
            </tr>
            <tr>
                <td colspan="4">TOTAL SDM SALES</td>
                <td>SPV SALES</td>
                <td colspan="2"><?= $total_spv_sales;?></td>
                <td>SALESFORCE</td>
                <td colspan="4"><?= $total_salesforce;?></td>
            </tr>
            <tr>
                <td colspan="4">LUAS RUANG FINANCE - in Meter2</td>
                <td>TOTAL</td>
                <td colspan="2"><?= $luas_ruang_finance;?> m2</td>
                <td>PANJANG</td>
                <td colspan="2"><?= $panjang_ruang_finance;?> m</td>
                <td>LEBAR</td>
                <td><?= $lebar_ruang_finance;?> m</td>
            </tr>
            <tr>
                <td colspan="4">TOTAL SDM FINANCE</td>
                <td>ADM SPV</td>
                <td colspan="2"><?= $total_admin_spv?> Org</td>
                <td>KASIR</td>
                <td colspan="2">Org</td>
                <td>FAKTURIS</td>
                <td>Org</td>
            </tr>
            <tr>
                <td colspan="4">LUAS RUANG LOGISTIK - in Meter2</td>
                <td>TOTAL</td>
                <td colspan="2"><?= $luas_ruang_logistik;?> m2</td>
                <td>PANJANG</td>
                <td colspan="2"><?= $panjang_ruang_logistik;?> m</td>
                <td>LEBAR</td>
                <td><?= $lebar_ruang_logistik;?> m</td>
            </tr>
            <tr>
                <td colspan="4">TOTAL SDM LOGISTIK</td>
                <td>KALOG</td>
                <td colspan="2"><?= $total_kalog;?> Org</td>
                <td>ADM LOGISTIK</td>
                <td colspan="2"><?= $total_admin_logistik;?> Org</td>
                <td>ADM EKSPEDISI</td>
                <td><?= $total_admin_ekspedisi;?> Org</td>
            </tr>
            <tr>
                <td colspan="4">LUAS GUDANG ARSIP - in Meter2</td>
                <td>TOTAL</td>
                <td colspan="2"><?= $luas_gudang_arsip;?> m2</td>
                <td>PANJANG</td>
                <td colspan="2"><?= $panjang_gudang_arsip;?> m</td>
                <td>LEBAR</td>
                <td><?= $lebar_gudang_arsip;?> m</td>
            </tr>
        </tbody>
    </table>
    </div>
</body>

</html>