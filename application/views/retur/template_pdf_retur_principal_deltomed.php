<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MPM SITE | AJUAN RETUR</title>
    <link rel="stylesheet" href="{{ asset('landing-page') }}/style.css" />
    <style>
    
      .header{
        text-align: center;
        margin-bottom: 2rem;
        font-weight: 700;
      }

      .table_bingkai_luar{
        width: 100%;
        /* border-collapse: collapse; */
        border: 1px solid;
      }

      .header-detail{
        margin-bottom: 10px;
        font-weight: 500;
        font-size: 12px;
      }

      .header-container{
        display: flex;
      }

      .header-prolog{
        margin-bottom: 10px;
        font-weight: 500;
        font-size: 12px;
      }

      .header-prolog-no{
        font-weight: 500;
        font-size: 12px;
        text-align: right;
      }

      .header-last{
        margin-bottom: 10px;
        font-weight: 500;
        font-size: 12px;
        text-align: left;
      }

      .table-product, .th-product, .td-product{
        border-collapse: collapse;
        border: 1px solid;
        /* width: 100%; */
      }

      .th-product{
        font-size: 13px;
        font-weight: 700;
        text-align: center;
      }

      .td-product{
        font-size: 12px;
        padding: 8px;
        text-align: left;
      }

      .table-footer, .th-footer, .td-footer{
        /* border-collapse: collapse; */
        border: none;
        width: 100%;
      }

      .td-footer{
        /* font-size: 13px; */
        /* padding: 8px; */
        text-align: center;
        vertical-align: top;
        width: auto;
      }

    </style>
  </head>
  <body>
      <section id="about" class="about">
        <div class="header">
            <!-- <?= $no_pengajuan; ?><br> -->
            FORM PENGEMBALIAN BARANG RETUR (FBR)
        </div>

        <div class="row">
            <div class="content">
                <div class="header-detail">
                    <b>Nama Distributor : <?= $company.' / '.$site_code; ?><br>
                    <b>Category Produk yang diretur : <?= $category; ?><br>
                </div>                
                <div class="header-container">
                    <div class="header-prolog">
                    <b>Kepada Yth <br>
                        PT Deltomed Laboratories <br>
                        Cc PT MPM
                    </div>
                    <div class="header-prolog-no">
                    <b>Nomor FBR : <?= $no_pengajuan; ?>
                    </div>                    
                </div>
                <div class="header-last">
                  <b>Berikut ini kami mengajukan permohonan pengembalian barang (retur) dari cabang kami, dengan detail sbb :
                </div>
            </div>
        </div>

        <div class="row">
          <div class="content">

            <table class="table-product">
              <tr>
                <th class="th-product" style="width: 1px;">Kode Produk</th>
                <th class="th-product" style="width: 1px;">Nama Produk</th>
                <th class="th-product" style="width: 1px;">No. Batch</th>
                <th class="th-product" style="width: 1px;">Unit/Sat</th>
                <th class="th-product" style="width: 10px;">Exp Date</th>
                <th class="th-product" style="width: 1px;">Qty</th>
                <th class="th-product" style="width: 10px;">Nama Outlet (*)</th>
                <th class="th-product" style="width: 1px;">Alasan Retur (*)</th>
                <th class="th-product" style="width: 230px;">Keterangan Tambahan</th>
              </tr>
            <?php 
            $no = 1;
            foreach ($detail as $row) { ?>
              <tr>
                <td class="td-product"><?= $row->kodeprod; ?></td>
                <td class="td-product"><?= $row->namaprod; ?></td>
                <td class="td-product"><?= $row->batch_number; ?></td>
                <td class="td-product"><?= $row->satuan; ?></td>
                <td class="td-product"><?= $row->expired_date; ?></td>
                <td class="td-product"><?= $row->jumlah; ?></td>
                <td class="td-product"><?= $row->nama_outlet; ?></td>
                <td class="td-product"><?= $row->alasan; ?></td>
                <td class="td-product"><?= $row->keterangan; ?></td>
              </tr>
              <?php } ?>
            </table>
          </div>
        </div>

        <br>
        <div class="row">
            <table width="100%" border="0">
                <tr>
                    <td width="50%">
                    </td>
                    <td><center><font size="13px">Diajukan oleh,</center></font></td>
                    <td><center><font size="13px">Dicek oleh,</center></td>
                    <td><center><font size="13px">Disetujui oleh,</center></td>
                </tr>
                <tr>
                    <td>
                    <font size="13px">
                    (**) Alasan Retur : <br>
                    A <span class="spasi">&nbsp;</span>Cacat Produksi (sesuai kriteria)<br> 
                    B <span class="spasi">&nbsp;</span>Kadaluarsa (sesuai ketentuan)<br> 
                    C <span class="spasi">&nbsp;</span>Produk Discontinue<br> 
                    D <span class="spasi">&nbsp;</span>Penarikan Pabrik<br> 
                    E <span class="spasi">&nbsp;</span>Lain-lain<br>
                    </font>
                    </td>
                    <td><center><font size="13px">
                    <?php 

                      if (file_exists("c:/xampp/htdocs/cisk/assets/uploads/signature/$username-signature.png")) { ?>
                        <img src="c:/xampp/htdocs/cisk/assets/uploads/signature/<?= $username ?>-signature.png" alt="ttd" width="90cm">    
                        <!-- <?php echo "aaaa"; ?> -->
                      <?php }else{
                        echo '';
                      }
                    ?>
                    </center></td>
                    <td style="vertical-align: bottom;"><center><font size="13px">
                      <img src="C:/xampp/htdocs/cisk/assets/css/images/ttd_p_fakhrul_stempel.jpg" alt="ttd" width="90cm"></center></td>
                    <td><center><font size="13px"></center></td>
                </tr>
                
                <tr>
                    <td></td>
                    <td><center><font size="13px"><?= $nama ?><br><b><?= $company ?></b></center></td>
                    <td><center><font size="13px">Fakhrul Hidayat<br><b>PT MPM</td>
                    <td><center><font size="13px"><br><b>Deltomed Laboratories</b></center></td>
                </tr>
                
                <tr>
                    <td></td>
                    <td colspan="3"><font size="13px"><br><b>Keputusan Deltomed atas ajuan Retur (Divisi S&M):</b><br>Disetujui / Ditolak (***)
                      
                    <br><br><b>Keputusan Deltomed atas Fisik Barang Retur :</b>
                    <br>Fisik barang dikirim ke Pabrik Deltomed / Dilakukan Pemusnahan di lokasi outlet/DP (***)
                    </td>
                </tr>
            </table>
        </div>
        <br>
      </section>
    </body>
</html>