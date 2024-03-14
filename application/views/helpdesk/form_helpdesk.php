<?php
foreach ($site_code as $a) {
    $site_dp = $a->site_code;
    $subbranch_dp = $a->nama_comp;
    $company_dp = $a->company;
    $site[$a->site_code] = $a->nama_comp.' - '.$a->site_code;
}
?>

<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <div class="d-inline">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                </div>
            </div>

        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <div class="page-body">
                    <div class="row">

                        <!-- =================================================== Form Helpdesk ========================================== -->
                        <div class="col-md-12 col-xl-5">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                                <div class="card-block">
                                    <?php echo form_open_multipart('/helpdesk/add_helpdesk'); ?>

                                    <div class="form-group row admin">
                                        <label class="col-sm-3">Sub Branch</label>
                                        <div class="col-sm-9">
                                            <?php
                                            echo form_dropdown('site_code', $site, '', 'class="form-control"  id="site_code" required');
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group row dp" hidden>
                                        <label class="col-sm-3">Site Code</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="site_code" value="<?= $site_dp; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row dp">
                                        <label class="col-sm-3">Sub Branch</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="subbranch" value="<?= $subbranch_dp; ?>" readonly required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3">Nama</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="name" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">No. Telp</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="telp" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3">Masalah</label>
                                        <div class="col-sm-9">
                                            <select name="masalah" class="form-control" id="masalah" required>
                                                <option value="">Pilih</option>
                                                <option value="1">Barang Kurang</option>
                                                <option value="2">Barang Lebih</option>
                                                <option value="3">Barang Rusak</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="customize">
                                        <label class="col-sm-3"></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="custom" placeholder="Masalah" id="custom">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3">Kronologis</label>
                                        <div class="col-sm-9">
                                            <textarea id='deskripsi' name="deskripsi" rows="6" cols="90%" class="form-control" required maxlength="150"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Berita Acara</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="userfile" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label" style="color: rgb(0 0 0 / 30%);">(Opsional)</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="userfile2" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label" style="color: rgb(0 0 0 / 30%);">(Opsional)</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="userfile3" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label" style="color: rgb(0 0 0 / 50%);">Video</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="video" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row" style="justify-content: center;">
                                    <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success btn-round center-block" required'); ?>
                                    <?php echo form_close(); ?>
                                </div>
                                <br>
                            </div>
                        </div>

                        <!-- ================================================ History ==================================================================== -->

                        <div class="col-md-12 col-xl-7">
                            <div class="card comp-card">
                                <div class="card-header">
                                    <h5>History</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="card-block">
                                                <div class="dt-responsive table-responsive">
                                                    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <font size="2px">Status
                                                                </th>
                                                                <th>
                                                                    <font size="2px">No. Ticket
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Nama
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Tanggal
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Masalah
                                                                </th>
                                                                <th class="admin"></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($history as $a) : ?>
                                                                <tr>
                                                                    <td>
                                                                        <font size="2px">
                                                                            <?php if ($a->status == '0') { ?>
                                                                                <button class="btn btn-primary btn-round btn-sm disabled">OPEN</button>
                                                                            <?php } elseif ($a->status == '1') { ?>
                                                                                <button class="btn btn-warning btn-round btn-sm disabled">PROCESS</button>
                                                                            <?php } elseif ($a->status == '2') { ?>
                                                                                <button class="btn btn-success btn-round btn-sm disabled">CLOSED</button>
                                                                            <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><a href="<?= base_url() . "helpdesk/note_helpdesk/" .md5($a->id_tiket); ?>"><?= $a->id_tiket; ?></a>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><?= $a->name; ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><?= date("d-m-Y",strtotime($a->created_date)); ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px">
                                                                            <?php
                                                                            if ($a->id_kategori == '1') {
                                                                                echo "Barang Kurang";
                                                                            } elseif ($a->id_kategori == '2') {
                                                                                echo "Barang Lebih";
                                                                            } elseif ($a->id_kategori == '3') {
                                                                                echo "Barang Rusak";
                                                                            }
                                                                            ?>
                                                                    </td>
                                                                    <td class="admin">
                                                                        <?php if ($a->status == '0') { ?>
                                                                            <a href="<?= base_url() . "helpdesk/proses_helpdesk/" .md5($a->id_tiket); ?>" type="button" class="btn btn-warning btn-mat btn-round btn-sm admin" role="button">
                                                                                PROCESS
                                                                            </a>
                                                                            <!-- <a href="<?= base_url() . "helpdesk/success_helpdesk/" .md5($a->id_tiket); ?>" type="button" class="btn btn-success btn-mat btn-round btn-sm adminx" role="button">
                                                                                CLOSED
                                                                            </a> -->
                                                                        <?php } elseif ($a->status == '1') { ?>
                                                                            <a href="<?= base_url() . "helpdesk/success_helpdesk/" .md5($a->id_tiket); ?>" type="button" class="btn btn-success btn-mat btn-round btn-sm admin" role="button">
                                                                                CLOSED
                                                                            </a>
                                                                        <?php } else { ?>
                                                                            <a href="<?= base_url() . "helpdesk/success_helpdesk/" .md5($a->id_tiket); ?>" type="button" class="btn btn-success btn-mat btn-round btn-sm admin disabled" role="button">
                                                                                CLOSED
                                                                            </a>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                            $y = substr($a->last_updated,0,4);
                                                                            $m = substr($a->last_updated,5,2);
                                                                            $d = substr($a->last_updated,8,2);

                                                                            $cy = substr($a->created_date,0,4);
                                                                            $cm = substr($a->created_date,5,2);
                                                                            $cd = substr($a->created_date,8,2);
                                                                            //A: RECORDS TODAY'S Date And Time
                                                                            $today = time();
                                                                            //B: RECORDS Date And Time OF YOUR EVENT
                                                                            $event = mktime(0,0,0,$m,$d,$y);
                                                                            $created = mktime(0,0,0,$cm,$cd,$cy);
                                                                            //C: COMPUTES THE DAYS UNTIL THE EVENT.
                                                                            $countdown = round(($today - $event)/86400);
                                                                            $closed = round(($event - $created)/86400);
                                                                            //D: DISPLAYS COUNTDOWN UNTIL EVENT

                                                                            if($a->status < '2'){
                                                                                echo "$countdown days";
                                                                            }else{
                                                                                echo "$closed days";
                                                                            }    
                                                                            
                                                                            // if($a->status < '1'){
                                                                            //     if($countdown == 2){
                                                                            //         echo "$countdown days";
                                                                            //         // redirect("helpdesk/email_reminder/".md5($a->id_tiket));
                                                                            //     }else{
                                                                            //         echo "$countdown days";
                                                                            //     }
                                                                            // }elseif ($a->status == '1') {
                                                                            //     if($countdown == 3){
                                                                            //         echo "$countdown days";
                                                                            //         redirect("helpdesk/success_helpdesk/".md5($a->id_tiket));
                                                                            //     }else{
                                                                            //         echo "$countdown days";
                                                                            //     }
                                                                            // }else{
                                                                            //     echo "$closed days";
                                                                            // }  
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>

                                                        </tbody>

                                                        <tfoot>
                                                            <tr>
                                                                <th>
                                                                    <font size="2px">Status
                                                                </th>
                                                                <th>
                                                                    <font size="2px">No. Ticket
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Nama
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Tanggal
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Masalah
                                                                </th>
                                                                <th class="admin"></th>
                                                                <th></th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
<!-- =========================================== View Image ============================================ -->
<?php $this->load->view('helpdesk/view_image'); ?>

<script>
    $(document).ready(function() {
        var userid = "<?= $this->session->userdata('id'); ?>";
        if (userid == 547 || userid == 297 || userid == 588 || userid == 857 ) {
            $('.dp').remove();
        } else {
            $('.admin').remove();
        }

        $("#site_code").click(function() {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>helpdesk/get_company_sitecode",
                data: {
                    site_code: $(this).val(),
                },
                success: function(data) {
                    console.log(data)
                    $('#company').html(data).change();
                }
            });
        });

        $("#site_code").click(function() {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>helpdesk/get_subbranch_sitecode",
                data: {
                    site_code: $(this).val(),
                },
                success: function(data) {
                    console.log(data)
                    $('#subbranch').html(data).change();
                }
            });
        });

        $('#customize').hide()
        $("select#masalah").change(function() {
            var selectedLocation = $(this).children("option:selected").val();
            if (selectedLocation == 'lain') {
                $('#customize').show()
                $("input#custom").attr("required", "true")
            } else {
                $('#customize').hide()
                $("input#custom").removeAttr("required")
            }
        });
    });
</script>

<!-- <script type="text/javascript">

var countDate = new Date('Jan 20 2022 00:00:00').getTime();

function newYear(){
    var now = new Date().getTime();
    gap = countDate - now;

    var detik = 1000;
    var menit = detik * 60;
    var jam = menit * 60;
    var hari = jam * 24;

    var h = Math.floor(gap / (hari));
    var j = Math.floor( (gap % (hari)) / (jam) );
    var m = Math.floor( (gap % (jam))  / (menit) );
    var d = Math.floor( (gap % (menit))  / (detik) );

    document.getElementById('hari').innerText = h;
    document.getElementById('jam').innerText = j;
    document.getElementById('menit').innerText = m;
    document.getElementById('detik').innerText = d;
}

setInterval( function(){
    newYear();
}, 1000);

</script> -->