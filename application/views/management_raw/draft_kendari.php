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

<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>

    <div class="card-block">
        <div class="row">
            <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>distributor</th>
                            <th>cabang</th>
                            <th>kodeproduk</th>
                            <th>mapping kodeproduk</th>
                            <th>namaproduk</th>
                            <th>kodecustomer</th>
                            <th>namacustomer</th>
                            <th>tahun-bulan</th>
                            <th>grossamount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($get_raw_draft->result() as $a) : ?>
                        <tr>
                            <td><?= $a->NAMAAREA; ?></td>
                            <td><?= $a->AREA; ?></td>
                            <td><?= $a->KODEPROD; ?></td>
                            <td><?= $a->kodeprod_mpm; ?></td>
                            <td><?= $a->NAMAPROD; ?></td>
                            <td><?= $a->KODELANG; ?></td>
                            <td><?= $a->NAMALANG; ?></td>
                            <td><?= date("Y - m" ,strtotime($a->TGLDOKJDI)); ?></td>
                            <td><?= $a->TOTHNA; ?></td>
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
            "order": [[4, 'asc']]
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