<?php
$req='<sup>*</sup>';
$attr = array(
    'style' => 'size: 100;',
);
$id=$list->id;
$username=$list->username;
$name=$list->name;
$email=$list->email;
$email_finance=$list->email_finance;
$charge=$list->charge;
$company=$list->company;
$address=$list->address;
$npwp=$list->npwp;
$phone=$list->phone;
$alamat_wp=$list->alamat_wp;
$nama_wp=$list->nama_wp;
//$list->free_result();
$data = array( 'id'  => $id );

?>
<div>
    <button type="button" class="btn btn-primary btn-sm btn-round" id="edit">
        Edit
    </button>
    <button type="button" class="btn btn-warning btn-sm btn-round" data-toggle="modal" data-target="#password">
        Change Password
    </button>
</div>
<br>

<?php echo form_open("profile/account_edit/$id"); ?>
<div class="row">
    <div class="col-6">
        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Username</label>
                <div class="col-sm-9">
                    <input class="form-control" type="text" name="username" value="<?php echo $username;?>" readonly/>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9">
                    <input class="form-control user" type="text" name="email" value="<?php echo $email;?>" />
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Email Finance</label>
                <div class="col-sm-9">
                    <input class="form-control user" type="text" name="email_finance" value="<?php echo $email_finance; ?>" />
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama</label>
                <div class="col-sm-9">
                    <input class="form-control user" type="text" name="name" value="<?php echo $name; ?>" />
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama Perusahaan</label>
                <div class="col-sm-9">
                    <input class="form-control user" type="text" name="company" value="<?php echo $company; ?>" />
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">PIC (Person In Charge)</label>
                <div class="col-sm-9">
                    <input class="form-control user" type="text" name="charge" value="<?php echo $charge; ?>" />
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Phone / Fax</label>
                <div class="col-sm-9">
                    <input class="form-control user" type="text" name="phone" value="<?php echo $phone; ?>" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">NPWP</label>
                <div class="col-sm-9">
                    <input class="form-control user" type="text" name="npwp" value="<?php echo $npwp; ?>" />
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nama NPWP</label>
                <div class="col-sm-9">

                    <input class="form-control user" type="text" name="nama_wp" value="<?php echo $nama_wp; ?>" />
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat (NPWP)</label>
                <div class="col-sm-9">
                    <input class="form-control" style="width:100%;height:80px;" type="text" name="alamat_wp"
                        value="<?php echo $alamat_wp; ?>" readonly/>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Alamat Kantor (Pengiriman)</label>
                <div class="col-sm-9">
                    <input class="form-control" style="width:100%;height:80px;" type="text" name="address"
                        value="<?php echo $address; ?>" readonly/>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12 save" align="center">
    <hr>
    <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success btn-round btn-sm"'); ?>
    <?php echo form_close(); ?>
</div>


<!-- ----------------------------------------- Modal Password ------------------------------------------------ -->
<div class="modal fade" id="password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open("profile/password_save"); ?>
            <div class="modal-body">
                <div class="col-sm-12">
                    <?PHP echo 'Change password for username <b>'.$username.'</b>';?>
                    <br><br>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">New Password</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="password" name="password" value="" />
                            <br>
                            <?PHP
                        echo form_submit('submit','Simpan', 'class="btn btn-success btn-sm"');
                        echo form_close();
                    ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('.user').attr('readonly', true);
        $('.save').hide();

        $("#edit").click(function () {
            $('.user').attr('readonly', false);
            $('.save').show();
        });
    });
</script>