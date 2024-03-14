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

<!-- <?php echo form_open_multipart($url); ?> -->
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
                            <td><?= $a->grossamount * 1.11; ?></td>
                            <td><?= $a->netamount; ?></td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>
            </div>
        </div>
        <hr>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Lanjut Proses
        </button>
    </div>
</div>

<?= $this->load->view('management_raw/confirm_data'); ?>

<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "pageLength": 10,
            "aLengthMenu": [
                [1000, 2000, -1],
                [1000, 2000, "All"]
            ],
        });

        $(".loading").hide();
        $(".submit").hide();
        $(function () {
            $('select[name=status_closing]').on('change', function () {
                var status = $(this).children("option:selected").val();
                if (status == 'null') {
                    $(".submit").hide();
                } else {
                    $(".submit").show();
                }
            });
        });
        $(".submit").click(function () {
            $(".loading").show();
            $(".submit").hide();
        });
    });

</script>