<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MES | MPM E-commerce System</title>
    <link rel="stylesheet" href="{{ asset('landing-page') }}/style.css" />
    <style>

    .th-product{
    font-size: 13px;
    font-weight: 700;
    text-align: center;
    }

    .td-product-left{
    font-size: 12px;
    padding: 8px;
    text-align: left;
    }

    .td-product-center{
    font-size: 12px;
    padding: 8px;
    text-align: center;
    }

    .table-product{
        border: 1px solid;
    border-collapse: collapse;
    }

    </style>
  </head>
  <body>
      <section id="about" class="about">
        <div class="header"><center>
            Nomor Pesanan Gudang
        </center>
        </div>

        <br>

        <div class="row">
            <div class="content">

                <table border="0" width="100%" class="tabel_header">
                    <tr>
                        <td width="10%">No</td>
                        <td width="40%">: <?= $npg.' | '.$nama_olshop. ' | '.$nama_store ?></td>
                    </tr>
                    <tr>
                        <td width="10%">Tanggal</td>
                        <td width="40%">: <?= $tgl_pesanan_gudang ?></td>
                    </tr>
                </table>

            </div>
        </div>

        <br>

        <div class="row">
          <div class="content">

            <table class="table-product" width="100%" border="1">
              <tr>
                <th class="th-product">ProductId</th>
                <th class="th-product">Nama Product</th>
                <th class="th-product">Total Qty</th>
                <th class="th-product">Box</th>
                <th class="th-product">Sachet</th>
              </tr>
            <?php 
            $no = 1;
            foreach ($detail->result() as $row) { ?>
              <tr>
                <td class="td-product-left"><?= $row->productid; ?></td>
                <td class="td-product-left"><?= $row->nama_product; ?></td>
                <td class="td-product-center"><?= $row->qty; ?></td>
                <td class="td-product-center"><?= $row->box; ?></td>
                <td class="td-product-center"><?= $row->sachet; ?></td>
              </tr>
              <?php } ?>
            </table>
          </div>
        </div>

        <br>
        <div class="row">
            <table width="100%" border="0">
                <tr>
                    <td width="30%"><center><font size="13px">Diajukan oleh,</center></font></td>
                    <td width="30%"><center><font size="13px">Dikeluarkan oleh,</center></td>
                    <td width="30%"><center><font size="13px">Diperiksa oleh,</center></td>
                </tr>
                <tr>
                    <td style="vertical-align: bottom;"><center><font size="13px">
                    <br><br><br><br><br>
                    (____________)
                    </center></td>
                    <td style="vertical-align: bottom;"><center><font size="13px">
                    <br><br><br><br><br>
                    (____________)
                    </center></td>
                    <td style="vertical-align: bottom;"><center><font size="13px">
                    <br><br><br><br><br>
                    (____________)
                    </center></td>
                </tr>


            </table>
        </div>
        <br>
      </section>
    </body>
</html>