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
        width: 50%;
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
                    <th colspan="12">DATA ASSET</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">JENIS ASSET</td>
                    <td colspan="10"><?= strtoupper($jenis_asset);?></td>
                </tr>
                <tr>
                    <td colspan="2">MEREK</td>
                    <td colspan="10"><?= strtoupper($merk);?></td>
                </tr>
                <tr>
                    <td colspan="2">TYPE</td>
                    <td colspan="10"><?= strtoupper($type);?></td>
                </tr>
                <tr>
                    <td rowspan="3">DATA PEMBELIAN ASSET</td>
                    <td>TH PEMBELIAN</td>
                    <td colspan="10"><?= substr($tanggal_pembelian,0,4);?></td>
                </tr>
                <tr>
                    <td>BULAN</td>
                    <td colspan="10"><?= substr($tanggal_pembelian,5,2);?></td>
                </tr>
                <tr>
                    <td>TGL PEMBELIAN</td>
                    <td colspan="10"><?= substr($tanggal_pembelian,8,2);?></td>
                </tr>
                <tr>
                    <td colspan="2">DIVISI PEMAKAI</td>
                    <td colspan="10"><?= strtoupper($divisi_pemakai);?></td>
                </tr>
                <tr>
                    <td colspan="2">JABATAN PEMAKAI</td>
                    <td colspan="10"><?= strtoupper($jabatan_pemakai);?></td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>
</body>

</html>