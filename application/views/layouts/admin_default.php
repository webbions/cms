<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?php echo isset($page_title) ? $page_title : 'Webbions | Dashboard'; ?>
    </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.css">
	<!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url("assets/plugins/iCheck/all.css") ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url("assets/plugins/select2/select2.min.css") ?>">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?php echo base_url("assets/plugins/datepicker/datepicker3.css") ?>">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url("assets/plugins/daterangepicker/daterangepicker-bs3.css") ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/plugins/fileupload/jquery.fileupload.css") ?>">

    <!-- jvectormap -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css">
	<!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url("assets/plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

     <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.pipeline.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.responsive.min.js" type="text/javascript"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="<?=base_url("assets/plugins/daterangepicker/daterangepicker.js")?>"></script>
    <!-- datepicker -->
    <script src="<?=base_url("assets/plugins/datepicker/bootstrap-datepicker.js")?>"></script>
    <!-- Select2 -->
    <script src="<?=base_url("assets/plugins/select2/select2.full.min.js")?>"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url(); ?>assets/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>assets/dist/js/app.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="<?=base_url("assets/plugins/iCheck/icheck.min.js")?>"></script>
    <!-- Sparkline -->
    <script src="<?php echo base_url(); ?>assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- Block UI -->
    <script src="<?php echo base_url(); ?>assets/plugins/blockUI/jquery.blockui.js" type="text/javascript"></script>
    <!-- jvectormap -->
    <script src="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="<?php echo base_url(); ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- Waves -->
    <script src="<?=base_url("assets/plugins/waves/waves.min.js")?>"></script>
    <!-- The basic File Upload plugin -->
    <!-- <script src="<?php echo base_url() ?>assets/plugins/fileupload/jquery.fileupload.js"></script> -->
    <!-- ChartJS 1.0.1 -->
    <script src="<?php echo base_url(); ?>assets/plugins/chartjs/Chart.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- <script src="<?php echo base_url(); ?>assets/dist/js/pages/dashboard2.js"></script> -->
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/common.js"></script>

    <script>
        $(function() {
            //Initialize Select2 Elements
            $(".select2").select2();
            //Date range picker
            $('.date-range').daterangepicker();
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-green'
            });
            $('.iCheck-helper').click(function() {
                $(this).closest("tr").toggleClass("warning", 3000);
            });
            $('a[data-confirm]').click(function() {
                $(this).closest("tr").toggleClass("danger", 850);
            });

            $('body').on("click", "a[data-confirm]", function(ev) {
                var href = $(this).attr('href');
                if (!$('#dataConfirmModal').length) {
                    $('body').append('<div id="dataConfirmModal" class="modal modal-danger"> <div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button><h4 id="dataConfirmLabel" class="modal-title"><i class="ion ion-help-circled"></i> Please Confirm</h4></div><div class="modal-body"></div><div class="modal-footer"><button data-dismiss="modal" class="btn wave btn-outline pull-left" type="button">No, Cancel</button><a class="btn wave btn-outline" href="#" id="dataConfirmOK">Yes I am</a></div></div></div></div>');
                }
                $('#dataConfirmModal').find('.modal-body').html($(this).attr('data-confirm'));
                $('#dataConfirmOK').attr('href', href);
                $('#dataConfirmModal').modal({show: true});
                return false;
            });
        });
    </script>

  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <!-- Header -->
        <?php echo $this->load->view('layouts/admin_header', null, true); ?>
        <!-- Side bar -->
        <?php echo $this->load->view('layouts/admin_sidebar', null, true); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                <?php echo $page_header; ?>
              </h1>
              <!-- <ol class="breadcrumb"> -->
                <?php echo generate_breadcrumb(); ?>
                <!-- <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li> -->
              <!-- </ol> -->
            </section>

            <?php echo $body; ?>

        </div><!-- /.content-wrapper -->
        <!-- Side bar -->
        <?php echo $this->load->view('layouts/admin_footer', null, true); ?>
        <!-- Control Sidebar -->
        <?php //echo $this->load->view('layouts/admin_control_sidebar', null, true); ?>

    </div><!-- ./wrapper -->


  </body>
</html>
