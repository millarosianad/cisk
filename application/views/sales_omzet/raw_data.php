<div>
    <strong>
        Informasi !! :

        <br> Harian = akan update setiap hari tanpa memperhatikan closing tiap bulannya.
        <br> Closing Bulanan = akan di proses dengan memperhatikan closing data nasional.
        <br> Pastikan download dengan tuntas. Dan juga re-check ukuran file yang sudah di download dengan informasi
        "size" di tabel menu ini.
        <br> Jika ada keraguan dengan raw data ini bisa hubungi IT.
    </strong>
    <br><br>
    <!-- Button trigger modal -->
    <?php 
    $id = $this->session->userdata('id');
    if ($id == '547' || $id =='297' || $id =='561' ) {
        echo '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#bonusModal">
                Upload Bonus
            </button>';
    }
    ?>
</div>
<br>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th>
                                <font size="2px">Principal
                            </th>
                            <th>
                                <font size="2px">Nama
                            </th>
                            <th>
                                <font size="2px">Keterangan
                            </th>
                            <th>
                                <font size="2px">Download
                            </th>
                            <th>
                                <font size="2px">Size
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($proses as $a) : ?>
                        <tr>
                            <td>
                                <font size="2px">
                                    <?php 
                                if ($a->supp == '001') {
                                    echo "Deltomed";
                                }elseif ($a->supp == '002'){                               
                                    echo "Marguna";
                                }elseif ($a->supp == '005'){                               
                                    echo "Ultra Sakti";
                                }elseif ($a->supp == '012'){                               
                                    echo "Intrafood";
                                }elseif ($a->supp == '004'){                               
                                    echo "Jaya Agung";
                                }elseif ($a->supp == '013'){                               
                                    echo "Strive";
                                }elseif ($a->supp == '014'){                               
                                    echo "HNI";
                                }elseif ($a->supp == '015'){                               
                                    echo "MDJ";
                                }else{
                                    echo "Custom";
                                }
                                 ?>
                            </td>
                            <td>
                                <font size="2px"><?php echo $a->nama; ?>
                            </td>
                            <td>
                                <font size="2px"><?php echo $a->keterangan; ?>
                            </td>
                            <td>
                                <font size="2px">
                                    <?php 
                                    if ($a->target == null) {
                                        echo "belum tersedia";
                                    } else{ ?>
                                    <?php 
                                        echo anchor("http://backup.muliaputramandiri.com:81/cisk/assets/file/raw_data/".$a->target, 'download', "class='btn btn-primary btn-sm'");
                                    ?>
                                    <!-- <a href="<?php echo base_url()."assets/file/raw_data/".$a->target; ?>"class="btn btn-danger btn-sm" role="button">download</a> -->
                                    <?php } ?>
                            </td>
                            <td>
                                <?php 

                                    // $url = "http://backup.muliaputramandiri.com:81/cisk/assets/file/raw_data/$a->target";
                                    // $ch = curl_init($url);
                                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                    // curl_setopt($ch, CURLOPT_HEADER, TRUE);
                                    // curl_setopt($ch, CURLOPT_NOBODY, TRUE);
                                    // $data = curl_exec($ch);
                                    // $fileSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
                                    // $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                    // curl_close($ch);

                                    // echo $this->model_sales_omzet->formatBytes($fileSize,1);
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>
                                <font size="2px">Principal
                            </th>
                            <th>
                                <font size="2px">Nama
                            </th>
                            <th>
                                <font size="2px">Request
                            </th>
                            <th>
                                <font size="2px">Download
                            </th>
                            <th>
                                <font size="2px">Size
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('sales_omzet/modal_bonus');
?>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            "ordering": false,
            "lengthMenu": [
                [10, 25, 50, 100, 150, -1],
                [10, 25, 50, 100, 150, "All"]
            ]
        });
    });
</script>
</body>

</html>