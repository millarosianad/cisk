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
        font-size: 100%;
        padding-left: 4px;
    }

    table th,
    table td {
        overflow: hidden !important;
        white-space: normal !important;
    }
</style>

<body>
    <div>
        <table class="table table-bordered">
            <thead align="center">
                <tr>
                    <th colspan="12">Data Karyawan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3">NAMA (SESUAI KTP)</td>
                    <td colspan="9"><?= strtoupper($nama);?></td>
                </tr>
                <tr>
                    <td colspan="3">JENIS KELAMIN</td>
                    <td colspan="9"><?= strtoupper($jenis_kelamin);?></td>
                </tr>
                <tr>
                    <td colspan="3">TEMPAT / TGL LAHIR</td>
                    <td colspan="9"><?= strtoupper($tempat.'. '.$tanggal_lahir);?></td>
                </tr>
                <tr>
                    <td colspan="3">TINGKAT PENDIDIKAN</td>
                    <td colspan="9"><?= strtoupper($tingkat_pendidikan); ?></td>
                </tr>
                <tr>
                    <td colspan="3">STATUS PERNIKAHAN</td>
                    <td colspan="9"><?= strtoupper($status_pernikahan); ?></td>
                </tr>
                <tr>
                    <td colspan="3">DEPARTEMEN</td>
                    <td colspan="9"><?= strtoupper($department); ?></td>
                </tr>
                <tr>
                    <td colspan="3">DIVISI</td>
                    <td colspan="9"><?= strtoupper($department); ?></td>
                </tr>
                <tr>
                    <td colspan="3">JABATAN</td>
                    <td colspan="9"><?= strtoupper($jabatan); ?><</td>
                </tr>
                <tr>
                    <td colspan="3">STATUS KARYAWAN</td>
                    <td colspan="9"><?= strtoupper($status_karyawan); ?></td>
                </tr>
                <tr>
                    <td colspan="3">TANGGAL MASUK KERJA</td>
                    <td colspan="9"><?= strtoupper($tanggal_masuk_kerja); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>
</body>

</html>