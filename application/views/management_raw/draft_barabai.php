<style>

td {
  text-align: left;
  font-size: 12px;
}

th {
  text-align: left;
  font-size: 13px;
}

</style>

<?php echo form_open_multipart($url); ?>
<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>

    <div class="card-block">    
        <div class="row">
            <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th>distributor</th>
                            <th>cabang</th>
                            <th>kodeproduk</th>
                            <th>namaproduk</th>
                            <th>kodecustomer</th>
                            <th>namacustomer</th>
                            <!-- <th>alamat</th>
                            <th>kodesalesman</th>
                            <th>namasalesman</th> -->
                            <th>tahun-bulan</th>
                            <th>grossamount</th>
                            <th>netamount</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_raw_draft->result() as $a) : ?>
                        <tr>
                            <!-- <td>
                                <center>
                                    <input type="checkbox" id="<?php echo $a->id; ?>" name="options[]" class = "<?php echo $a->id; ?>" value="<?php echo $a->id; ?>">
                                </center>
                            </td> -->
                            <td><?= $a->distributor; ?></td>
                            <td><?= $a->cabang; ?></td>
                            <td><?= $a->kodeproduk; ?></td>
                            <td><?= $a->namaproduk; ?></td>
                            <td><?= $a->kodecustomer; ?></td>
                            <td><?= $a->namacustomer; ?></td>
                            <!-- <td><?= $a->alamatcustomer; ?></td>
                            <td><?= $a->kodesalesman; ?></td>
                            <td><?= $a->namasalesman; ?></td> -->
                            <td><?= $a->tahunbulan; ?></td>
                            <td><?= $a->grossamount; ?></td>
                            <td><?= $a->netamount; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-12">
                <center><h4>SUMMARY PRA-MAPPING</h4>    
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                Count Row / Jumlah Row Original
            </div>
            <div class="col-md-3">
                : <font size = "4px"><?= $get_summary->row()->count_raw ?> Row</font> 
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-3">
                Sum Bruto / Sum GrossAmount * 1.11
            </div>
            <div class="col-md-3">
                : <font size = "4px">Rp. <?= number_format($get_summary->row()->raw_bruto) ?></font> 
            </div>
            <div class="col-md-3">
                Sum GrossAmount / exclude PPN
            </div>
            <div class="col-md-3">
                : <font size = "4px">Rp. <?= number_format($get_summary->row()->raw_exclude_ppn) ?></font> 
            </div>
        </div>

        <hr>

        <center>
        <div class="row mt-5">
            <div class="col-md-12">
                <input type="hidden" name="signature" value="<?= $signature ?>">
                <?php echo form_submit('submit', 'lanjut proses mapping barabai', 'class="btn btn-primary"'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
        </center>
    </div>
        
</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "pageLength": 10,
            "aLengthMenu": [
                [1000, 2000, -1],
                [1000, 2000, "All"]
            ],
        });
    });
</script>