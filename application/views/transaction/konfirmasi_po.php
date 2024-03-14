<div class="dt-responsive table-responsive">
    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
        <thead>
            <tr>
                <th></th>
                <th>
                    Tanggal Po
                </th>
                <th>
                    Principal
                </th>
                <th>
                    Nomor Po
                </th>
                <th>
                    Company
                </th>
                <th>
                    Tipe
                </th>
                <th>
                    SubBranch
                </th>
                <th>
                    TotalSKU
                </th>
            </tr>
        </thead>
        <tbody>
            <?php  foreach($get_po as $a) : ?>
            <tr>
                <td>
                    <?php 
                            if ($a->x == $a->y) {
                                echo anchor(base_url()."transaction/konfirmasi_po_detail/".$a->supp.'/'.$a->id,"&nbsp;",[
                                    'class'=>'fa fa-check-circle fa-lg',
                                    'style'=>"color:green"
                                ]);
                            }elseif($a->y == null){
                                echo anchor(base_url()."transaction/konfirmasi_po_detail/".$a->supp.'/'.$a->id,"&nbsp;",[
                                    'class'=>'fa fa-exclamation-circle fa-lg',
                                    'style'=>"color:red"
                                ]);
                            }else{
                                echo anchor(base_url()."transaction/konfirmasi_po_detail/".$a->supp.'/'.$a->id,"&nbsp;",[
                                    'class'=>'fa fa-exclamation-circle fa-lg',
                                    'style'=>"color:orange"
                                ]);
                            }
                        ?>
                </td>
                <td>
                    <?php echo $a->tglpo; ?>
                </td>
                <td>
                    <?php echo $a->namasupp; ?>
                </td>
                <td>
                    <?php 
                        if ($a->nopo == null) {
                            echo "<i>".anchor(base_url()."transaction/download_pdf/".$a->id,'belum tersedia',['target' => '_blank']);
                        }else{
                            echo anchor(base_url()."transaction/download_pdf/".$a->id,$a->nopo,['target' => '_blank']);
                        } ?>
                </td>
                <td>
                    <?php echo $a->company; ?>
                </td>
                <td>
                    <?php echo $a->tipe; ?>
                </td>
                <td>
                    <?php echo $a->nama_comp; ?>
                </td>
                <td>
                    <?php echo $a->x; ?>
                </td>

            </tr>

            <?php endforeach; ?>

        </tbody>

    </table>
</div>