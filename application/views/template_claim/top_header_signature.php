
<!-- signature -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js" ></script>
    
    <link
      href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css"
      rel="stylesheet"
    />
    <link href="<?= base_url() ?>assets/css/jquery.signature.css" rel="stylesheet" />
    <style>
      .kbw-signature {
        width: 400px;
        height: 200px;
      }
    </style>

    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    
    <script src="<?= base_url() ?>assets/js/jquery.ui.touch-punch.min.js"></script>


<!-- <script src="<?= base_url() ?>assets/js/jquery.ui.touch-punch.js"></script> -->

    <script src="<?= base_url() ?>assets/js/jquery.signature.js"></script>
    <script>
      $(function () {
        var sig = $("#sig").signature();
        $("#disable").click(function () {
          var disable = $(this).text() === "Disable";
          $(this).text(disable ? "Enable" : "Disable");
          sig.signature(disable ? "disable" : "enable");
        });
        $("#clear").click(function () {
          sig.signature("clear");
        });
        $("#json").click(function () {
          alert(sig.signature("toJSON"));
        });
        $("#svg").click(function () {
          alert(sig.signature("toSVG"));
        });
      });
    </script>