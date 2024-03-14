<?php echo form_open($url); ?>

<div class="card">

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2">Alasan</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" name="alasan" required>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <!-- <?php echo form_submit('submit', 'Tambah Alasan', '" class="btn btn-primary"'); ?> -->
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <hr>

    <div class="col-sm-12">
        <div class="form-group row">
            <div class="col-sm-12">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th width="100%"><font size="2px">alasan</th>
                            <th width="100%"><font size="2px">createdAt</th>
                            <th width="100%"><font size="2px">createdBy</th>
                            <th width="100%"><font size="2px">#</th>
                        </tr>
                    </thead>
                    <tbody>                                        
                        <?php foreach ($get_alasan->result() as $a) : ?>
                        <tr>
                            <td><font size="2px"><?= $a->alasan; ?></td>                            
                            <td><font size="2px"><?= $a->created_at; ?></td>
                            <td><font size="2px"><?= $a->username; ?></td>
                            <td>
                            <?php 
                                echo anchor('retur/delete_alasan/'.$a->signature,
                                        ' ',
                                        array(
                                            'class' => 'fa fa-times fa-2x', 'style' => 'color:red',
                                            'onclick' => 'return confirm(\'Yakin menghapus row ini ?\')'
                                        )
                                    );
                                    ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

