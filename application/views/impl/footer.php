</div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <!-- <script src="<?php echo base_url();?>/vendor/jquery/jquery.min.js"></script> -->
    <script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url();?>vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url();?>vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <!-- <script src="<?php echo base_url();?>/vendor/raphael/raphael.min.js"></script>
    <script src="<?php echo base_url();?>/vendor/morrisjs/morris.min.js"></script>
    <script src="<?php echo base_url();?>/js/morris-data.js"></script> -->

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url();?>js/sb-admin-2.js"></script>

    <!-- DataTable JavaScript -->
    <script defer src="<?php echo base_url();?>js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url();?>js/dataTables.responsive.js"></script>

    <!-- X-editable BS3 -->
    <link href="https://vitalets.github.io/x-editable/assets/x-editable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <script src="https://vitalets.github.io/x-editable/assets/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
    <link href="https://vitalets.github.io/x-editable/assets/select2/select2.css" rel="stylesheet" />
    <script defer src="https://vitalets.github.io/x-editable/assets/select2/select2.js"></script>
    <link href="https://vitalets.github.io/x-editable/assets/select2/select2-bootstrap.css" rel="stylesheet" />

    <?php
      if(is_logged_in()){
    ?>
    <script src="<?php echo base_url();?>js/jquery.timeago.js"></script>
    <script src="<?php echo base_url();?>js/notify.js"></script>
    <script src='https://cn1.super7tech.com/socket.io/socket.io.js'></script>
    <script src="<?php echo base_url();?>js/common_script.js"></script>
    <link href="<?php echo base_url();?>css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <script src="<?php echo base_url();?>js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url();?>js/jquery.autocomplete.js"></script>
    <script src="<?php echo base_url();?>js/jquery.titlealert.min.js"></script>
    <link href="<?php echo base_url();?>css/animate.css" rel="stylesheet">
    <script src="<?php echo base_url();?>js/jquery.mask.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js"></script>
    <link rel="stylesheet" href="https://afarkas.github.io/webshim/js-webshim/minified/shims/styles/shim-ext.css">
    <script src="https://afarkas.github.io/webshim/js-webshim/minified/polyfiller.js"></script>

    <?php
      }
    ?>

</body>

</html>
