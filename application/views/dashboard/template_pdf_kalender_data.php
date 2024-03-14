<!doctype html>
<html>

<head>
    <!-- <title><?= ucwords($header->kode); ?></title> -->
</head>
<style>
    img {
        position: absolute;
        top: 0px;
        left: 0px;
    }

    h1 {
        font-family: "Times New Roman", Times, serif;
    }

    .tabel1 {
        font-family: sans-serif;
        color: #444;
        border-collapse: collapse;
        border: 1px solid #f2f5f7;
        white-space: normal !important;
    }

    .tabel1 tr th {
        background: #FF7F00;
        color: #fff;
        font-weight: normal;
    }

    .tabel1,
    th,
    td {
        padding: 10px 20px;
        text-align: left;
        font-size: 12px;
    }

    .tabel1 tr:hover {
        background-color: #f5f5f5;
    }

    .tabel1 tr:nth-child(even) {
        background-color: #f2f2f2;
    }


    /*
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
    } */
</style>

<body>
    <img src="C:/xampp/htdocs/cisk/assets/css/images/mpm_new.jpg" class="img" alt="logo" width="70cm">
    <center>
        <h1 style="font-size: 20px;">PT MULIA PUTRA MANDIRI</h1>
        <h1 style="font-size: 15h1x;">Data Upload <?= date("d F Y") ?></p>
            <br><br>
            <table cellspacing="0" cellpadding="0" width="100%" class="tabel1">
                <tbody>
                    <tr>
                        <th width="2%">No</th>
                        <th width="5%">Kode</th>
                        <th width="25%">Sub Branch</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Belum Kirim</th>
                    </tr>
                    <?php $i = '1';
                        foreach ($detail as $value) : ?>
                    <tr>
                        <td><?= $i++?></td>
                        <td><?= $value->kode;?></td>
                        <td><?= $value->nama_comp;?></td>
                        <td>
                            <?php if($value->tgl == null) {
                                "";
                            }else{
                                echo date("d F Y", strtotime($value->tgl));
                            };?>
                        </td>
                        <td><?= $value->terlambat;?> hari</td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
    </center>
</body>

</html>