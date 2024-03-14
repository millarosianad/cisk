<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <title>MPM Site | Surat Jalan</title>
    <style>
        .img {
            position: absolute;
            top: 0px;
            left: 0px;
        }

        h1 {
            font-family: "Times New Roman", Times, serif;
            font-size: 30px;
        }

        .header {

            font-size: 12px;
            font-family: "Times New Roman", Times, serif;
            vertical-align: top;
            padding: 5px;
        }

        th {
            font-size: 14px;
        }

        td {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <img src="C:/xampp/htdocs/cisk/assets/css/images/mpm_new.jpg" class="img" alt="logo" width="70cm">
    <center>
        <h1>SURAT JALAN</h1>
    </center>
    <br><br>
    <div class="row">
        <div class="col">

            <!-- <?php var_dump($detail); ?> -->

            <table class="header" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                    <tr>
                        <td class="header" width="100px">No Surat Jalan</td>
                        <td class="header" width="2px">:</td>
                        <td class="header" width="350px"><b><?= $kode; ?></b></td>
                        <td class="header" width="50px">Tgl</td>
                        <td class="header" width="2px">:</td>
                        <td class="header"><?= $created_at; ?></td>
                    </tr>
                    <tr>
                        <td class="header" width="100px">Pemesan</td>
                        <td class="header" width="2px">:</td>
                        <td class="header" width="350px"><b><?= $company; ?></b></td>
                        <!-- <td class="header" width ="50px">Tgl. Dok</td>
                    <td class="header" width = "2px">:</td>
                    <td class="header"><?= $created_at; ?></td> -->
                    </tr>
                    <tr>
                        <td class="header">Alamat</td>
                        <td class="header">:</td>
                        <td class="header"><?= $alamat; ?></td>
                    </tr>
                    <tr>
                        <td class="header">PO Ref</td>
                        <td class="header">:</td>
                        <td class="header"><?= $nopo; ?> (pada tanggal <?= $tglpo; ?>) </td>
                    </tr>
                    <tr>
                        <td class="header">DO Ref</td>
                        <td class="header">:</td>
                        <td class="header"><?= $nodo; ?> (pada tanggal <?= $tgldo; ?>) </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table table-bordered" style="height: 10px;">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Kode Prc</th>
                        <th>Nama Produk</th>
                        <th>Unit</th>
                        <th>Karton</th>
                        <th>Berat</th>
                        <th>Volume</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($detail as $key) { ?>
                        <tr>
                            <td><?= $key->kodeprod; ?></td>
                            <td><?= $key->kode_prc; ?></td>
                            <td><?= $key->namaprod; ?></td>
                            <td><?= $key->banyak; ?></td>
                            <td><?= $key->banyak_karton; ?></td>
                            <td><?= $key->total_berat; ?></td>
                            <td><?= $key->total_volume; ?></td>
                        </tr>
                    <?php }
                    ?>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="4"><strong>TOTAL</strong></td>
                        <td><?= $total_karton; ?></td>
                        <td><?= $total_berat; ?></td>
                        <td><?= $total_volume; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


    <div class="row">
        <div class="col">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <!-- <th width="20%">diserahkan oleh</th>
                        <th width="20%">dikirim oleh</th>
                        <th width="20%">diterima oleh</th> -->
                        <th width="25%"></th>
                        <th width="25%"></th>
                        <th width="25%"></th>
                        <th width="25%"><?= $created_at_format; ?> </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Diserahkan oleh<br><br><br><br><br><br>PT. MPM</th>
                        <th scope="row">Dikirim oleh<br><br><br><br><br><br>PT. Hitori Jaya</th>
                        <th scope="row">Diterima oleh<br><br><br><br><br><br>DP</th>
                        <th scope="row">
                            Penanggung Jawab <br>
                            <img src="C:/xampp/htdocs/cisk/assets/css/images/ttd_p_fakhrul_stempel.jpg" alt="ttd" width="90cm"><br>
                            Fakhrul Hidayat
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <!-- <div class="row">
        <div class="col-md-5">
            NOTE : <br>
            <?= $header->note; ?>
        </div>

        <div class="col-md-5">
            Jakarta, <?= $header->tglpo; ?><br>
            Penanggung Jawab <br>
            <img src="C:/xampp/htdocs/cisk/assets/css/images/ttd.jpg" alt="ttd" width="90cm"><br>
            Herman Oscar
        </div>
    </div> -->




    <!-- 
    
    <div class="note">
        NOTE : <br>
        <?= $header->note; ?>
    </div>
    <div class="ttd" style="text-align: center;">
        Jakarta, <?= $header->tglpo; ?><br>
        Penanggung Jawab <br>
        <img src="C:/xampp/htdocs/cisk/assets/css/images/ttd.jpg" alt="ttd" width="90cm"><br>
        Herman Oscar
    </div> -->


    <!-- <div class="row">
        <div class="col-3">
        <table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Handle</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
            </tr>
            <tr>
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>@fat</td>
            </tr>
            <tr>
            <th scope="row">3</th>
            <td>Larry</td>
            <td>the Bird</td>
            <td>@twitter</td>
            </tr>
        </tbody>
        </table>
        </div>
    </div> -->


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    -->
</body>

</html>