<style>
   
    .th-event{
        font-weight: bold;
        background-color: #FFEAA7;
        border: 0.5px solid #383838;
        color: #000000;
        font-size: 13px;
    }
    .td-event{
        background-color: #ffffff;
        border: 0.5px solid #000000;
        font-size: 12px;
        /* line-height: 5px; */
        overflow:hidden;
    }

    .th-review{
        font-weight: bold;
        background-color: #f0f0f0;
        border: 0.5px solid #383838;
        color: #000000;
        font-size: 13px;
        border-radius: 10px;
    }
    .td-review{
        background-color: #ffffff;
        border: 0.1px solid #000000;
        font-size: 12px;
        /* line-height: 5px; */
        overflow:hidden;
        height: 100px;
        border-radius: 10px;
    }

    table {
        border-collapse: collapse;
    }

    .btn-submit {
        color: #f0f0f0;
        background-color: #383838;
        border-radius: 5px;
    }

    .btn-submit:hover {
        color: #f0f0f0;
        background-color: #555454;
    }

    .btn-pending {
        color: #2b2929;
        background-color: #d5d4d4;
        border-radius: 5px;
        border: 1px solid black;
        padding: 5px 15px 5px 15px;
    }

    .btn-pending:hover {
        color: #f0f0f0;
        background-color: #555454;
    }

    .btn-generate {
        color: #f0f0f0;
        background-color: #638889;
        border-radius: 5px;
        border: 1px solid black;
        padding: 5px 15px 5px 15px;
    }
   
    .btn-delete{
        color: #f0f0f0;
        background-color: brown;
        border-radius: 5px;
        border: 1px solid black;
        padding: 5px 5px 5px 5px;
    }

    .btn-edit{
        color: #f0f0f0;
        background-color: #5b82a1;
        border-radius: 5px;
        border: 1px solid black;
        padding: 5px 5px 5px 5px;
    }

    a:link { text-decoration: none; }
    a:visited { text-decoration: none; }
    a:hover { text-decoration: none; }
    a:active { text-decoration: none; }
    
</style>



</div>

<div class="container">

<div class="row mt-5 ms-5">
    <div class="col-md-12 az-content-label text-center">
        <?php 
            if($this->session->flashdata('pesan')){ ?>
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('pesan'); ?>
                </div>
            <?php
            }elseif($this->session->flashdata('pesan_success')){ ?>
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata('pesan_success'); ?>
                </div>
            <?php
            }
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

</form>

    <?= form_open_multipart($url); ?>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Tulis Review Anda</label>
        </div>
        <div class="col-md-5">
            <textarea name="review" cols="30" rows="10" placeholder="isi review anda disini" class="form-control"></textarea>
        </div>
    </div>

    <input type="hidden" name="signature_pelaporan" value="<?= $signature ?>">


    <div class="row mt-5">
        <div class="col-md-2"></div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-generate">Tambah Review</button>
            <a href="<?= base_url('management_event/pelaporan') ?>" class="btn btn-pending">Kembali</a>
        </div>
    </div>


    <?= form_close();?>
    
    <hr>

</div>

<div class="container">
    <div class="row mt-5 ms-5">
        <div class="col-md-12 az-content-label text-center">
            History Review
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="review" class="display" style="overflow-x: scroll; width: 100%;">
                <thead>
                    <tr>
                        <th class="th-review text-center col-1">No</th>
                        <th class="th-review text-center col-1">ReviewBy</th>
                        <th class="th-review text-center col-2">ReviewAt</th>
                        <th class="th-review text-center">Review</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    $no = 1;
                    foreach ($get_review->result() as $a) : ?>
                    <tr>
                        <td class="td-review" align="center"><?= $no++ ?></td>
                        <td class="td-review"><?= $a->name ?></td>                       
                        <td class="td-review"><?= $a->created_at ?></td>                       
                        <td class="td-review"><?= $a->review ?></td>                       
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>

        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row mt-5 ms-5">
        <div class="col-md-12 az-content-label text-center">
            History Pelaporan Event
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="event" class="display" style="overflow-x: scroll; width: 100%;">
                <thead>
                    <tr>
                        <th class="th-event text-center">NoEvent</th>
                        <th class="th-event text-center">Status</th>
                        <th class="th-event text-center">Nama</th>
                        <th class="th-event text-center">Tanggal</th>
                        <th class="th-event text-center">Lokasi</th>
                        <th class="th-event text-center">Ref Rpd</th>
                        <th class="th-event text-center">Value</th>
                        <th class="th-event text-center">Attachment</th>
                        <th class="th-event text-center">#</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    $no = 1;
                    foreach ($get_pelaporan_event->result() as $a) : ?>
                    <tr>
                        <td class="td-event" align="center"><?= $a->no_pelaporan_event ?></td>
                        <td>
                            
                            <a href="<?= base_url() . 'management_event/review/' . $a->signature ?>">
                                <?= $a->nama_status ?>
                            </a>
                        </td>
                        <td class="td-event"><?= $a->nama_event ?></td>
                        <td class="td-event"><?= $a->event_from ?></td>
                        <td class="td-event"><?= $a->lokasi_event ?></td>
                        <td class="td-event"><?= $a->referensi_rpd ?></td>
                        <td class="td-event"><?= $a->value_omzet ?></td>
                        <td class="td-event" align="center">

                            <?php 
                                if ($a->attach_1) { ?>
                                    <a href="<?= base_url() . 'assets/uploads/management_event/'.$a->attach_1 ?>" target="_blank" class="btn-pending">attach_1</a>
                                <?php
                                }else{
                                    echo "-";
                                }

                                if ($a->attach_2) { ?>
                                    <a href="<?= base_url() . 'assets/uploads/management_event/'.$a->attach_2 ?>" target="_blank" class="btn-pending">attach_2</a>
                                <?php
                                }else{
                                    echo "-";
                                }

                                if ($a->attach_3) { ?>
                                    <a href="<?= base_url() . 'assets/uploads/management_event/'.$a->attach_3 ?>" target="_blank" class="btn-pending">attach_3</a>
                                <?php
                                }else{
                                    echo "-";
                                }
                            ?>
                            
                        </td>
                        <td class="td-event" align="center">
                            
                            <a href="<?= base_url('management_event/pelaporan_delete/'.$a->id) ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: white"></i> Delete</a>
                            <a href="<?= base_url('management_event/pelaporan_edit/'.$a->id) ?>" class="btn-edit"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                        </td>
                       
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>

        </div>
    </div>
</div>
   

<script>
    $(document).ready(function () {
        $("#event").DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            },
            scrollX: true
        });

        $("#review").DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            },
            scrollX: true
        });
    });
</script>




<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>