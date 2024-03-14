
    <div class="pcoded-inner-content" >
        <div class="main-body">
            <div class="page-wrapper">

                <div class="page-body">
                    <div class="row">
                        <!-- <div class="col-md-12 col-xl-8"> -->
                        <div class="col-sm-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5>RPD | Reject</h5>
                                </div>
                                <div class="card-block">
                                    <!-- <form action="http://localhost:8080/cisk/master_data/rpd_reject" method="post"> -->
                                    <form action="<?= base_url(); ?>master_data/rpd_reject" method="post">
                                        <input type="text" name="userid" value="<?= $signature;?>" hidden>
                                        <input type="text" name="signature" value="<?= $signature;?>" hidden>
                                        <textarea name="alasan_atasan" id="" class="form-control" cols="30" rows="5" placeholder="Masukkan Alasan"></textarea>
                                        <br>
                                        <input type="submit" class="btn btn-success" value="Submit">
                                    </form>