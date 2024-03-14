<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }
</style>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive mt-4">
                <table id="multi-colum-dt" class="table table-columned">
                    <thead>
                        <tr>
                            <th width="1"><font size="1px"><input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>                        
                            <th>Principal</th>
                            <th>Branch</th>
                            <th>SubBranch</th>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_list_raw as $key) : ?>
                            <tr>
                                <td><center>
                            <input type="checkbox" id="<?php echo $key->filename; ?>" name="options[]" class = "<?php echo $key->filename; ?>" value="<?php echo $key->filename; ?>"></center></td>
                                <td><?php echo $key->namasupp; ?></td>
                                <td><?php echo $key->branch_name; ?></td>
                                <td><?php echo $key->nama_comp; ?></td>
                                <td><?php echo $key->tahun; ?></td>
                                <td><?php echo $key->bulan; ?></td>
                                <td><?php echo $key->created_at; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <br><br>
            <button type="submit" class="btn btn-primary" name="download" value="1">Download Raw Data</button>
            <br>
            <hr>

        </div>
    </div>
</div>
