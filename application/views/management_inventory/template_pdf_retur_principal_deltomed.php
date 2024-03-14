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
        width: 100%;
      }

      .th-product{
        font-size: 13px;
        font-weight: 700;
        text-align: center;
      }

      .td-product{
        font-size: 11px;
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
                    <b>Category Produk yang diretur : <?= $namasupp; ?><br>
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
                <th class="th-product">Kode Produk Deltomed</th>
                <th class="th-product">Nama Produk</th>
                <th class="th-product">No. Batch</th>
                <th class="th-product">Unit/Sat</th>
                <th class="th-product">Exp Date (bln/thn)</th>
                <th class="th-product">Qty</th>
                <th class="th-product">Nama Outlet (*)</th>
                <th class="th-product">Alasan Retur (*)</th>
                <th class="th-product">Keterangan Tambahan</th>
              </tr>
            <?php 
            $no = 1;
            foreach ($get_pengajuan_detail->result() as $row) { ?>
              <tr>
                <td class="td-product"><?= $row->kodeprod; ?></td>
                <td class="td-product"><?= $row->namaprod; ?></td>
                <td class="td-product"><?= $row->batch_number; ?></td>
                <td class="td-product"><?= $row->satuan; ?></td>
                <td class="td-product"><?= $row->expired_date; ?></td>
                <td class="td-product"><?= $row->qty_approval; ?></td>
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
                    <td width="30%">
                    </td>
                    <td><center><font size="13px">Diajukan oleh,</center></font></td>
                    <td><center><font size="13px">Disetujui oleh,</center></td>
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
                    <td>
                      <center><font size="13px">
                      <?php 
                      if ($digital_signature) { ?>
                        <img src="<?= './assets/uploads/signature/'.$digital_signature ?>" alt="<?= $digital_signature ?>" width="150px">
                      <?php
                      } ?>
                      </center>
                    </td>
                    <td>
                      <center><font size="13px">
                      <?php 
                      if ($principal_area_signature) { ?>
                        <img src="<?= './assets/uploads/signature/'.$principal_area_signature ?>" alt="<?= $principal_area_signature ?>" width="150px">
                      <?php
                      } ?>
                      </center>
                    </td>
                    <td style="vertical-align: bottom;"><center><font size="13px">
                    <?php if ($verifikasi_at) { ?>
                      <img src="./assets/css/images/ttd_p_fakhrul_stempel.jpg" alt="ttd" width="90cm"></center>
                    <?php
                    } ?>
                    </td>
                    <td>
                      <center><font size="13px">
                      <?php 
                          $file = './assets/uploads/signature/'.$principal_ho_signature;
                          if ($principal_ho_signature) { ?>
                              <img src="<?= $file ?>" alt="<?= $principal_ho_signature ?>" width="150px">
                          <?php
                          }
                      ?>
                      </center>
                    </td>
                </tr>
                
                <tr>
                    <td></td>
                    <td><center><font size="13px"><?= $nama ?><br><b>DP</center></td>
                    <td><center><font size="13px"><?= $principal_area_username ?><br><b>Principal Area</center></td>
                    <td><center><font size="13px">Fakhrul Hidayat<br><b>PT MPM</td>
                    <!-- <td><center><font size="13px"><?= $principal_ho_username ?><br><b>Deltomed Laboratories</b></center></td> -->
                    <td><center><font size="13px"><?= $principal_ho_username ?><br><b>Principal HO</b></center></td>
                </tr>
                
                <tr>
                    <td></td>
                    <td colspan="3"><font size="13px"><br><b>Keputusan Deltomed atas ajuan Retur (Divisi S&M):</b><br>
                    <?php
                      if ($status == 5 || $status == 6 || $status == 7 || $status == 8 || $status == 9) { ?>
                        Disetujui / <strike>Ditolak</strike>
                      <?php
                      }elseif($status == 10){ ?>
                        <strike>Disetujui</strike> / Ditolak
                      <?php
                      }else{
                        echo "Disetujui / Ditolak";
                      }
                    ?>
                      
                    <br><br><b>Keputusan Deltomed atas Fisik Barang Retur :</b><br>
                    <?php
                      if ($status == 5 || $status == 6 || $status == 8) { ?>
                        Fisik barang dikirim ke Pabrik Deltomed / <strike>Dilakukan Pemusnahan di lokasi outlet/DP</strike>
                      <?php
                      }elseif($status == 7 || $status == 9){ ?>
                        <strike>Fisik barang dikirim ke Pabrik Deltomed</strike> / Dilakukan Pemusnahan di lokasi outlet/DP
                      <?php
                      }else{
                        echo "Fisik barang dikirim ke Pabrik Deltomed / Dilakukan Pemusnahan di lokasi outlet/DP";
                      }
                    ?>
                    </td>
                </tr>
            </table>
        </div>
        <br>
      </section>
    </body>
</html>