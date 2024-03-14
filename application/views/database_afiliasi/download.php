<!-- Modal -->
<div class="modal fade" id="download" tabindex="-0" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Download</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">    
                <a id="demo1"></a>
                <a id="demo2"></a>
            </div>


        </div>
    </div>
</div>

<script>
    function Download(params) {
        $("#download").modal()
        let excel = "excel (csv)";
        let pdf = "pdf";

        if (params == 1) {
            result = "<a href='http://localhost:8080/cisk/database_afiliasi/download/<?= md5('profile')?>' class='btn btn-primary'>" + excel + "</a>";
            document.getElementById("demo1").innerHTML = result;

            result = "<a href='https://www.jjj.com' class='btn btn-danger'>" + pdf + "</a>";
            document.getElementById("demo2").innerHTML = result;
        } else if (params == 2) {
            result = "<a href='http://localhost:8080/cisk/database_afiliasi/download/<?= md5('gudang')?>' class='btn btn-primary'>" + excel + "</a>";
            document.getElementById("demo1").innerHTML = result;

            result = "<a href='https://www.jjj.com' class='btn btn-danger'>" + pdf + "</a>";
            document.getElementById("demo2").innerHTML = result;
        } else if (params == 3) {
            result = "<a href='http://localhost:8080/cisk/database_afiliasi/download/<?= md5('karyawan')?>' class='btn btn-primary'>" + excel + "</a>";
            document.getElementById("demo1").innerHTML = result;

            result = "<a href='https://www.jjj.com' class='btn btn-danger'>" + pdf + "</a>";
            document.getElementById("demo2").innerHTML = result;
        } else if (params == 4) {
            result = "<a href='http://localhost:8080/cisk/database_afiliasi/download/<?= md5('niaga')?>' class='btn btn-primary'>" + excel + "</a>";
            document.getElementById("demo1").innerHTML = result;

            result = "<a href='https://www.jjj.com' class='btn btn-danger'>" + pdf + "</a>";
            document.getElementById("demo2").innerHTML = result;
        } else if (params == 5) {
            result = "<a href='http://localhost:8080/cisk/database_afiliasi/download/<?= md5('non_niaga')?>' class='btn btn-primary'>" + excel + "</a>";
            document.getElementById("demo1").innerHTML = result;

            result = "<a href='https://www.jjj.com' class='btn btn-danger'>" + pdf + "</a>";
            document.getElementById("demo2").innerHTML = result;
        } else if (params == 6) {
            result = "<a href='http://localhost:8080/cisk/database_afiliasi/download/<?= md5('it_asset')?>' class='btn btn-primary'>" + excel + "</a>";
            document.getElementById("demo1").innerHTML = result;

            result = "<a href='https://www.jjj.com' class='btn btn-danger'>" + pdf + "</a>";
            document.getElementById("demo2").innerHTML = result;
        } else if (params == 7) {
            result = "<a href='http://localhost:8080/cisk/database_afiliasi/download/<?= md5('asset')?>' class='btn btn-primary'>" + excel + "</a>";
            document.getElementById("demo1").innerHTML = result;

            result = "<a href='https://www.jjj.com' class='btn btn-danger'>" + pdf + "</a>";
            document.getElementById("demo2").innerHTML = result;
        } else if (params == 8) {
            result = "<a href='http://localhost:8080/cisk/database_afiliasi/download/<?= md5('struktur')?>' class='btn btn-primary'>" + excel + "</a>";
            document.getElementById("demo1").innerHTML = result;

            result = "<a href='https://www.jjj.com' class='btn btn-danger'>" + pdf + "</a>";
            document.getElementById("demo2").innerHTML = result;
        }
    }
</script>