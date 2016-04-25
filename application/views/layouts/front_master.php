<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo isset($page_title) ? $page_title : 'TalentsList'; ?></title>
<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/theme/css/font-awesome.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/theme/css/style.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/theme/css/custom.css" type="text/css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/theme/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets/theme/js/bootstrap-filestyle.js"></script>
<script src="<?php echo base_url(); ?>assets/theme/js/jquery.form.js"></script>
</head>
<body>
    <!-- Header -->
   	 <?php echo $this->load->view('layouts/front_header', null, true); ?>
   	    	 
    <!-- Banner -->
    <?php //echo $this->load->view('layouts/front_banner', null, true); ?>
    
    <?php echo $body; ?>
    
    <!-- Footer -->
    <?php echo $this->load->view('layouts/front_footer', null, true); ?>
    
    
</body>

</html>