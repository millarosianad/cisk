<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Pengajuan Assets</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .line-title{
            border: 0;
            border-style: inset;
            border-top: 1px solid #000000;
        }
        .footer {
            position: absolute;
            right: 0;
            top: 800px;
            width: 100%;
           
        }
    </style>
</head>
<body>
    <img src="./assets/png/logo_mpm.png" style="position: absolute; width: 100px; height: auto;">
    <h3 align= "center"> PURCHASE ORDER </h3>
    
    <p align= "center"> No. PO : <?php echo "$no/MPM/$bln/$thn" ?> </p>
    <hr class="line-title">
    <br>
    <br>
    <table border="1" width="100%" cellspacing="-1" >
    <?php foreach($toko as $a) : ?>
            <tr>
                <th align= "left" width="40%">
                    Kepada Yth<br>
                    Nama :  <?php echo $a->nama_toko ?><br>
                    Alamat : <?php echo $a->alamat ?><br>
                    Phone : <?php echo $a->telp ?><br>
                    Fax : <?php echo $a->fax ?><br>
                    Attn : <?php echo $a->attn ?>
                </th>
                <th align= "left" width="60%">
                    <i>"Terima kasih untuk tidak memberikan hadiah atau imbalan
                    dalam bentuk apapun kepada seluruh karyawan kami"</i> <br><br>

                    <i>Pelanggaran terhadap hal ini akan mengakibatkan pemutusan hubungan bisnis dengan kami</i>
                </th>
            </tr>
    </table> 
    <br>
    <br>
    <table border="1" width="100%" cellspacing="-1" class="table table-respons">
    <thead>
        <tr>
            <th>NO</th>
            <th>Jumlah</th>
            <th>Nama Barang</th>
            <th>Harga Satuan</th>
            <th>Jumlah Harga</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $no = 1; 
    foreach($barang as $b) : ?>
        <tr>
            <td><?php echo $no++ ?></td>
            <td><?php echo $b->jumlah ?></td>
            <td><?php echo $b->nama_barang ?></td>
            <td>Rp. <?php echo number_format($b->harga) ?></td>
            <td>Rp. <?php echo number_format($b->sub_harga) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
    <?php 
    foreach($total as $c) : ?>
        <tr>
            <th colspan = "4">Total</th>
            <th align = "right">Rp. <?php echo number_format($c->sub_harga) ?></th>
        </tr>
        <tr>
            <th colspan = "4">PPN</th>
            <th align = "right">Rp. <?php echo number_format($c->sub_tax) ?></th>
        </tr>
        <tr>
            <th colspan = "4">Grand Total</th>
            <th align = "right">Rp. <?php echo number_format($c->total) ?></th>
        </tr>
    </tfoot>
    <?php endforeach; ?>
    </table>
<br>
<br>
Delivery : <br>The Mahitala Building <br>Jl. Alam Sutera Utama No. 6 <br> Alam Sutera - Tangerang
<br><br>
Time : 2 days
<br><br>
Term of Payment : COD
<div class="footer">
    <table border="0" width="100%" >

            <tr>
                <td align= "left" width="50%">
                    <b><u>Keterangan : </u></b><br>
                    Faktur Pajak Standar atas nama :<br>
                    PT. Mulia Putra Mandiri<br><br>
                    NPWP : 02.963.822.8-086.000
                </td>
                <td align= "center" width="50%">
                    Tangerang, <?php echo date('d F Y', strtotime($a->tgl_po)) ?>
                    <br><br><br><br><br>
                    Hendra
                </td>
            </tr>
    <?php endforeach; ?> 
    </table> 
</div>
</body>
</html>