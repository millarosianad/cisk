<style>
    td{
        font-size: 11px;
    }
    th{
        font-size: 12px; 
    }
</style>

</div>

<div class="container">
    
<div class="row">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-8">
        <table id="example" class="display" style="display: inline-block; overflow-y: scroll">
            <thead>
                <tr>
                    <th>site_code</th>
                    <th>branch_name</th>
                    <th class="col-5">nama_comp</th>
                    <th>status_claim</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($get_site->result() as $key) { ?>
                <tr>
                    <td><?= $key->site_code ?></td>
                    <td><?= $key->branch_name ?></td>
                    <td><?= $key->nama_comp ?></td>
                    <td>
                        <?php 
                            if ($key->status_claim) { ?>
                                <a href="<?= base_url()."management_claim/update_site/".$key->site_code ?>" class="btn btn-info btn-sm">aktif</a>
                            <?php    
                            }else{ ?>
                                <a href="<?= base_url()."management_claim/update_site/".$key->site_code ?>" class="btn btn-dark btn-sm">nonaktif</a>
                            <?php
                            }
                        ?>

                    </td>
                </tr>
                    <?php    
                    }
                ?>
            </tbody>
        </table>

    </div>
</div>



<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>