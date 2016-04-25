<section class="middel">
	<div class="page-title">
    	<div class="container">
        	<h2>Profile</h2>
        </div>
    </div>
  <form action="" enctype="multipart/form-data" name=="useraudioform" id="useraudioform" method="post">
		<div class="container">
    	<div class="signup">
        
        	<div class="login-with">
        	<a href="#"><img src="<?php echo base_url('assets/theme/images/facebook-login.png'); ?>" width="230" height="40" alt=""></a>
            <a href="#"><img src="<?php echo base_url('assets/theme/images/linkedinButton.png'); ?>" width="230" height="40" alt=""></a>
            </div>
          
            
                <div class="heading">
                    <h3>Add Audio Code</h3>
                </div>
                <div class="form">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Enter Audio Embedded <span class="req">*</span></label>
                                        <input type="text" value="" name="useraudio" id="useraudio" class="form-control" />
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form">
                    <div class="form-group">
                    <input type="submit" name="useraudio" value="Submit" class="btn btn-info btn-lg">
                    </div>
                </div>
        </div>
    </div>
    </form>
</section>

<script>
$("#useraudioform").validate({
    rules: {
		useraudio: {
			required: true,
			 
		}
    }
});
</script>