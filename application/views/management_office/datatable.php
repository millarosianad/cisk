<script>
      $(document).ready(function () {
        $("#kalender_data").DataTable({
            "pageLength": 10,
            // "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
        $("#monitoring_count").DataTable({
            "pageLength": 10,
            // "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
      });

      $(document).ready(function () {
        $("#status").DataTable({
            "pageLength": 100,
            // "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
      });

      $(document).ready(function () {
        $("#site").DataTable({
            "pageLength": 100,
            // "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
      });

      $(document).ready(function () {
        $("#kam").DataTable({
            "pageLength": 100,
            // "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
      });
</script>