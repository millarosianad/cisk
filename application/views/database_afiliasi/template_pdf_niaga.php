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

    th,td {
        border: 1px solid black;
        width: 10%;
        font-size: 100%;
        padding-left: 4px;
    }

    table th,table td {
        overflow: hidden !important;
        white-space: normal !important;
    }
</style>

<body>
    <div>
        <table class="table table-bordered">
            <thead align="center">
                <tr>
                    <th colspan="12">DATA NIAGA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">JENIS MOBIL</td>
                    <td colspan="10"><?= strtoupper($jenis_kendaraan);?></td>
                </tr>
                <tr>
                    <td colspan="2">BAHAN BAKAR</td>
                    <td colspan="10"><?= strtoupper($bahan_bakar);?></td>
                <tr>
                    <td colspan="2">NO POLISI</td>
                    <td colspan="10"><?= strtoupper($no_polisi);?></td>
                </tr>
                <tr>
                    <td colspan="2">TAHUN PEMBUATAN</td>
                    <td colspan="10"><?= strtoupper($tahun_pembuatan);?></td>
                </tr>
                <tr>
                    <td colspan="2">TANGGAL PAJAK BERAKHIR</td>
                    <td colspan="10"><?= $tanggal_pajak_berakhir;?></td>
                </tr>
                <tr>
                    <td colspan="2">TANGGAL PAJAK KIR</td>
                    <td colspan="10"><?= $tanggal_pajak_kir;?></td>
                </tr>
                <tr>
                    <td rowspan="3">KHUSUS SEWA</td>
                    <td>VENDOR</td>
                    <td colspan="10"><?= strtoupper($vendor);?></td>
                </tr>
                <tr>
                    <td>TGL AWAL SEWA</td>
                    <td colspan="10"><?= $tanggal_awal_sewa;?></td>
                </tr>
                <tr>
                    <td>TGL AKHIR SEWA</td>
                    <td colspan="10"><?= $tanggal_akhir_sewa;?></td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>
</body>

</html>