<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
   
    th{
        font-weight: bold;
        background-color: #FFEAA7;
        border: 0.5px solid #383838;
        color: #000000;
        font-size: 13px;
    }
    td{
        background-color: #ffffff;
        border: 0.5px solid #000000;
        font-size: 12px;
        /* line-height: 5px; */
        overflow:hidden;
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
        padding: 5px 5px 5px 5px;
    }

    .btn-pending:hover {
        color: #f0f0f0;
        background-color: #555454;
    }

    .btn-pdf {
        color: #f0f0f0;
        background-color: #154c79;
        border-radius: 5px;
    }

    .btn-pdf:hover {
        color: #f0f0f0;
        background-color: #5b82a1;
    }

    .btn-generate {
        color: #f0f0f0;
        background-color: #638889;
        border-radius: 5px;
        border: 1px solid black;
        padding: 5px 15px 5px 15px;
    }
    .btn-average:hover {
        color: #f0f0f0;
        background-color: #638889;
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
            <label for="nama_program">Nama Event</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="nama_event">
        </div>
    </div>
    
    
    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Tanggal Event</label>
        </div>
        <div class="col-md-5">
            <div class="input-group">
                <input type="datetime-local" name="from" class="form-control">
                <input type="datetime-local" name="to" class="form-control">
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Lokasi Event</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="lokasi_event">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Referensi RPD (Rencana Perjalanan Dinas)</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="ref_perdin">
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Value Omzet</label>
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" name="value_omzet">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama_program">Attachment</label>
        </div>
        <div class="col-md-5">
            <input type="file" class="form-control mb-2" name="attach1">
            <input type="file" class="form-control mb-2" name="attach2">
            <input type="file" class="form-control mb-2" name="attach3">
        </div>
    </div>


    <div class="row mt-2">
        <div class="col-md-2"></div>
        <div class="col-md-5">
            <button type="submit" class="btn btn-generate">Simpan Data</button>
        </div>
    </div>


    <?= form_close();?>
    
    <hr>

</div>

<div class="container">
    <div class="row mt-5 ms-5 mb-3">
        <div class="col-md-12 az-content-label text-center">
            History Pelaporan Event
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 d-flex justify-content-center">
            <div class="form-inline row">
                <div class="col-sm-12">
                    <form action="<?= base_url('management_event/pelaporan') ?>" method="get">
                        From
                        <input class="form-control" type="date" name="from" value="<?= $this->input->get('from') ?>" required />
                        To
                        <input class="form-control" type="date" name="to" value="<?= $this->input->get('to') ?>" required />
                        
                        <button type="submit" value="1" class="btn btn-pdf" name="type">Search</button>
                        <?php 
                            if ($this->session->userdata('supp') == 005) { ?>
                        
                            <?php
                            }else{ ?>
                                <button type="submit" value="2" class="btn btn-pending" name="type">Export To CSV</button>
                            <?php
                            }
                        ?>
                        <a href="<?= base_url() ?>management_event/pelaporan" class="btn btn-generate">View All</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="example" class="display" style="overflow-x: scroll; width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center">NoEvent</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Pelaksana</th>
                        <th class="text-center">NamaEvent</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Lokasi</th>
                        <th class="text-center">Ref Rpd</th>
                        <th class="text-center">Value</th>
                        <th class="text-center col-2">Attachment</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    $no = 1;
                    foreach ($get_pelaporan_event->result() as $a) : ?>
                    <tr>
                        <td align="center"><?= $a->no_pelaporan_event ?></td>
                        <td>
                            
                            <a href="<?= base_url() . 'management_event/review/' . $a->signature ?>">
                                <?= $a->nama_status ?>
                            </a>
                        </td>
                        <td><?= $a->name ?></td>
                        <td><?= $a->nama_event ?></td>
                        <td><?= $a->event_from ?></td>
                        <td><?= $a->lokasi_event ?></td>
                        <td><?= $a->referensi_rpd ?></td>
                        <td><?= number_format($a->value_omzet) ?></td>
                        <td>
                            <div class="col-12 d-flex justify-content-center gap-1">


                                <div class="col-md-4">
                                    <?php 
                                        if ($a->attach_1) { ?>
                                            <a href="<?= base_url() . 'assets/uploads/management_event/'.$a->attach_1 ?>" target="_blank" class="btn-pending">attach_1</a>
                                        <?php
                                        }else{
                                            echo "-";
                                        }
                                    ?>                        
                                </div>

                                <div class="col-md-4">
                                    <?php 
                                        if ($a->attach_2) { ?>
                                            <a href="<?= base_url() . 'assets/uploads/management_event/'.$a->attach_2 ?>" target="_blank" class="btn-pending">attach_2</a>
                                        <?php
                                        }else{
                                            echo "-";
                                        }
                                    ?>                        
                                </div>

                                <div class="col-md-4">
                                    <?php 
                                        if ($a->attach_3) { ?>
                                            <a href="<?= base_url() . 'assets/uploads/management_event/'.$a->attach_3 ?>" target="_blank" class="btn-pending">attach_3</a>
                                        <?php
                                        }else{
                                            echo "-";
                                        }
                                    ?>                        
                                </div>


                            </div>
                            
                            
                        </td>
                        <td align="center">

                            <div class="col-md-12 d-flex justify-content-center">
                                <div class="col-6 input-group">
                                    <a href="<?= base_url('management_event/pelaporan_delete/'.$a->signature) ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash-can" style="color: white"></i> Delete</a>
                                </div>
                                <div class="col-6 input-group">
                                    <a href="<?= base_url('management_event/pelaporan_edit/'.$a->signature) ?>" class="btn-edit"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                                </div>
                            </div>
                        </td>
                       
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>


            
        </div>
    </div>
    
    <hr>
    <br>
    <br>


<script>
    $(document).ready(function () {
    $("#example").DataTable({
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